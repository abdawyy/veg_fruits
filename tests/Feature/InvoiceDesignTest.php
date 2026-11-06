<?php

namespace Tests\Feature;

use App\Actions\Invoices\GenerateInvoicePdfAction;
use App\Filament\Pages\InvoiceDesignPage;
use App\Models\InvoiceDesign;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

final class InvoiceDesignTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_invoice_uses_the_saved_design(): void
    {
        $design = InvoiceDesign::current();
        $design->update([
            'brand_name' => 'Fresh Market',
            'thank_you' => ['en' => 'Enjoy your fruit.', 'ar' => 'بالهنا'],
            'accent_color' => '#0f766e',
            'header_color' => '#14532d',
        ]);

        $order = Order::query()->create([
            'reference' => 'AL-DESIGN-1',
            'customer_phone' => '01000000099',
            'customer_name' => 'Nour Hassan',
            'status' => 'pending',
            'payment_gateway' => 'cod',
            'subtotal' => '20',
            'packaging_fee' => '0',
            'discount_amount' => '0',
            'shipping_fee' => '5',
            'total' => '25',
        ]);
        OrderItem::query()->create([
            'order_id' => $order->id,
            'product_name_snapshot' => ['en' => 'Tangerine', 'ar' => 'يوسفي'],
            'unit' => 'kg',
            'quantity' => 2,
            'packaging' => 'bag',
            'unit_price' => 10,
            'line_total' => 20,
        ]);

        $html = view('pdf.invoice', [
            'order' => $order->fresh(['items', 'city', 'user']),
            'design' => $design->fresh(),
        ])->render();

        $this->assertStringContainsString('Fresh Market', $html);
        $this->assertStringContainsString('Enjoy your fruit.', $html);
        $this->assertStringContainsString('#0f766e', $html);
        $this->assertStringContainsString('Tangerine', $html);
        $this->assertStringContainsString('Nour Hassan', $html);
        $this->assertStringContainsString('25.00 EGP', $html);
        $this->assertStringContainsString('Cash on delivery', $html);

        Storage::fake('local');
        $path = app(GenerateInvoicePdfAction::class)->execute($order->fresh());
        $this->assertSame('invoices/AL-DESIGN-1.pdf', $path);
        $this->assertStringStartsWith('%PDF', Storage::disk('local')->get($path));
    }

    public function test_admin_can_edit_the_invoice_design(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        $order = Order::query()->create([
            'reference' => 'AL-OLD-PDF',
            'customer_phone' => '01000000099',
            'status' => 'pending',
            'payment_gateway' => 'cod',
            'subtotal' => '10',
            'packaging_fee' => '0',
            'discount_amount' => '0',
            'shipping_fee' => '0',
            'total' => '10',
            'invoice_path' => 'invoices/AL-OLD-PDF.pdf',
        ]);
        Storage::disk('local')->put('invoices/AL-OLD-PDF.pdf', '%PDF-old');

        $admin = User::factory()->admin()->create();
        $admin->assignRole(Role::findOrCreate('super_admin', 'web'));

        Livewire::actingAs($admin)
            ->test(InvoiceDesignPage::class)
            ->fillForm([
                'brand_name' => 'Market Stall',
                'accent_color' => '#112233',
                'header_color' => '#14532d',
                'thank_you' => ['en' => 'See you soon.', 'ar' => 'إلى اللقاء'],
            ])
            ->call('save');

        $design = InvoiceDesign::current();
        $this->assertSame('Market Stall', $design->brand_name);
        $this->assertSame('#112233', $design->accent_color);
        $this->assertSame('See you soon.', $design->localized('thank_you'));

        $order->refresh();
        $this->assertNull($order->invoice_path);
        Storage::disk('local')->assertMissing('invoices/AL-OLD-PDF.pdf');
    }
}
