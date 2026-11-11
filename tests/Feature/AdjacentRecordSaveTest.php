<?php

namespace Tests\Feature;

use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

final class AdjacentRecordSaveTest extends TestCase
{
    use RefreshDatabase;

    public function test_next_saves_the_open_record_then_opens_the_next_one(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        [$current, $next] = $this->products();

        Livewire::actingAs($this->admin())
            ->test(EditProduct::class, ['record' => $current->getRouteKey()])
            ->fillForm([
                'price_per_kg' => 27,
            ])
            ->call('saveAndOpenRecord', $next->getKey())
            ->assertHasNoFormErrors()
            ->assertRedirect(ProductResource::getUrl('edit', ['record' => $next->getKey()]));

        $this->assertSame(0, bccomp((string) $current->fresh()->price_per_kg, '27', 4));
    }

    public function test_next_stays_on_the_record_when_the_form_is_invalid(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        [$current, $next] = $this->products();

        Livewire::actingAs($this->admin())
            ->test(EditProduct::class, ['record' => $current->getRouteKey()])
            ->fillForm([
                'sku' => '',
                'price_per_kg' => 44,
            ])
            ->call('saveAndOpenRecord', $next->getKey())
            ->assertHasFormErrors(['sku'])
            ->assertNoRedirect();

        $this->assertSame(0, bccomp((string) $current->fresh()->price_per_kg, '10', 4));
    }

    /**
     * @return array{0: Product, 1: Product}
     */
    private function products(): array
    {
        $category = Category::query()->create([
            'name' => ['en' => 'Produce', 'ar' => 'خضار'],
            'slug' => ['en' => 'produce-nav', 'ar' => 'produce-nav'],
            'is_active' => true,
        ]);

        $current = Product::query()->create([
            'category_id' => $category->id,
            'name' => ['en' => 'Tomato', 'ar' => 'طماطم'],
            'slug' => ['en' => 'tomato-nav', 'ar' => 'tomato-nav'],
            'sku' => 'NAV-TOM',
            'price_per_kg' => 10,
            'is_active' => true,
        ]);

        $next = Product::query()->create([
            'category_id' => $category->id,
            'name' => ['en' => 'Cucumber', 'ar' => 'خيار'],
            'slug' => ['en' => 'cucumber-nav', 'ar' => 'cucumber-nav'],
            'sku' => 'NAV-CUC',
            'price_per_kg' => 12,
            'is_active' => true,
        ]);

        return [$current, $next];
    }

    private function admin(): User
    {
        $role = Role::findOrCreate('super_admin', 'web');
        foreach (['ViewAny:Product', 'View:Product', 'Update:Product'] as $name) {
            $role->givePermissionTo(Permission::findOrCreate($name, 'web'));
        }

        $admin = User::factory()->admin()->create();
        $admin->assignRole($role);

        return $admin;
    }
}
