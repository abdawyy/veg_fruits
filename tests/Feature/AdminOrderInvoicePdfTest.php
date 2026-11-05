<?php

namespace Tests\Feature;

use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Models\Order;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

final class AdminOrderInvoicePdfTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_admin_can_generate_an_invoice_pdf_for_every_order(): void
    {
        $missing = $this->order('AL-NO-PDF');
        $existing = $this->order('AL-HAS-PDF');
        $existing->forceFill(['invoice_path' => 'invoices/AL-HAS-PDF.pdf'])->save();
        Storage::disk('local')->put($existing->invoice_path, '%PDF-1.4 existing');

        Livewire::actingAs($this->admin())
            ->test(ListOrders::class)
            ->callAction('generate_invoices');

        $missing->refresh();
        $this->assertSame('invoices/AL-NO-PDF.pdf', $missing->invoice_path);
        Storage::disk('local')->assertExists('invoices/AL-NO-PDF.pdf');
        $this->assertStringStartsWith('%PDF', Storage::disk('local')->get('invoices/AL-NO-PDF.pdf'));
        $this->assertSame('%PDF-1.4 existing', Storage::disk('local')->get('invoices/AL-HAS-PDF.pdf'));

        Livewire::actingAs($this->admin())
            ->test(ListOrders::class)
            ->callAction(TestAction::make('downloadInvoice')->table($missing));

        $missing->refresh();
        $this->assertStringStartsWith('%PDF', Storage::disk('local')->get($missing->invoice_path));
    }

    private function admin(): User
    {
        $role = Role::findOrCreate('super_admin', 'web');
        $role->givePermissionTo(Permission::findOrCreate('ViewAny:Order', 'web'));

        $admin = User::factory()->admin()->create();
        $admin->assignRole($role);

        return $admin;
    }

    private function order(string $reference): Order
    {
        return Order::query()->create([
            'reference' => $reference,
            'customer_phone' => '01000000099',
            'customer_name' => 'Nour Hassan',
            'status' => 'pending',
            'payment_gateway' => 'cod',
            'subtotal' => '10',
            'packaging_fee' => '0',
            'discount_amount' => '0',
            'shipping_fee' => '0',
            'total' => '10',
        ]);
    }
}
