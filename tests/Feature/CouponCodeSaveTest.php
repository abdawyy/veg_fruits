<?php

namespace Tests\Feature;

use App\Filament\Resources\Coupons\Pages\CreateCoupon;
use App\Filament\Resources\Coupons\Pages\EditCoupon;
use App\Models\Coupon;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

final class CouponCodeSaveTest extends TestCase
{
    use RefreshDatabase;

    public function test_coupon_code_is_saved_in_uppercase(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        Livewire::actingAs($this->admin())
            ->test(CreateCoupon::class)
            ->fillForm([
                'code' => 'save10',
                'type' => Coupon::TYPE_PERCENT,
                'value' => 10,
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $coupon = Coupon::query()->sole();
        $this->assertSame('SAVE10', $coupon->code);

        Livewire::actingAs($this->admin())
            ->test(EditCoupon::class, ['record' => $coupon->getRouteKey()])
            ->fillForm([
                'code' => 'fresh20',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('FRESH20', $coupon->fresh()->code);
    }

    private function admin(): User
    {
        $role = Role::findOrCreate('super_admin', 'web');
        foreach (['ViewAny:Coupon', 'View:Coupon', 'Create:Coupon', 'Update:Coupon'] as $name) {
            $role->givePermissionTo(Permission::findOrCreate($name, 'web'));
        }

        $admin = User::factory()->admin()->create();
        $admin->assignRole($role);

        return $admin;
    }
}
