<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

final class InvoiceDownloadAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_signed_invoice_download_works_for_guest(): void
    {
        Storage::fake('local');
        $order = $this->orderWithInvoice();

        $url = URL::temporarySignedRoute('invoices.download', now()->addHour(), ['order' => $order->id]);

        $this->get($url)->assertOk();
    }

    public function test_logged_in_wrong_user_cannot_use_signed_link_for_another_order(): void
    {
        Storage::fake('local');
        $owner = User::factory()->create(['email' => 'owner@test.local']);
        $other = User::factory()->create(['email' => 'other@test.local']);

        $order = Order::query()->create([
            'reference' => 'AL-TEST-OWN',
            'user_id' => $owner->id,
            'customer_phone' => '01000000001',
            'customer_email' => 'owner@test.local',
            'status' => 'pending',
            'payment_gateway' => 'cod',
            'subtotal' => '10',
            'packaging_fee' => '0',
            'discount_amount' => '0',
            'shipping_fee' => '0',
            'total' => '10',
            'invoice_path' => 'invoices/AL-TEST-OWN.pdf',
        ]);
        Storage::disk('local')->put($order->invoice_path, '%PDF-1.4');

        $url = URL::temporarySignedRoute('invoices.download', now()->addHour(), ['order' => $order->id]);

        $this->actingAs($other)->get($url)->assertForbidden();
    }

    private function orderWithInvoice(): Order
    {
        $order = Order::query()->create([
            'reference' => 'AL-TEST-INV',
            'customer_phone' => '01000000099',
            'status' => 'pending',
            'payment_gateway' => 'cod',
            'subtotal' => '10',
            'packaging_fee' => '0',
            'discount_amount' => '0',
            'shipping_fee' => '0',
            'total' => '10',
            'invoice_path' => 'invoices/AL-TEST-INV.pdf',
        ]);
        Storage::disk('local')->put($order->invoice_path, '%PDF-1.4');

        return $order;
    }
}
