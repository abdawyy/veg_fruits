<?php

namespace Tests\Feature;

use App\Filament\Resources\HomeBanners\Pages\EditHomeBanner;
use App\Filament\Resources\HomeBanners\Pages\ListHomeBanners;
use App\Models\HomeBanner;
use App\Models\User;
use App\Support\HomeBannerAttributes;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

final class CreateMultipleHomeBannersImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_multiple_persists_uploaded_images(): void
    {
        Storage::fake('public');

        $role = Role::findOrCreate('super_admin', 'web');
        foreach (['ViewAny:HomeBanner', 'View:HomeBanner', 'Create:HomeBanner', 'Update:HomeBanner'] as $name) {
            $role->givePermissionTo(Permission::findOrCreate($name, 'web'));
        }

        $admin = User::factory()->admin()->create();
        $admin->assignRole($role);

        Filament::setCurrentPanel(Filament::getPanel('admin'));

        $component = Livewire::actingAs($admin)
            ->test(ListHomeBanners::class)
            ->mountAction('create_multiple');

        $banners = $component->instance()->mountedActions[0]['data']['banners'] ?? [];
        $itemKeys = array_keys($banners);
        $this->assertCount(2, $itemKeys);

        $titles = [
            ['en' => 'Mango', 'ar' => 'مانجو'],
            ['en' => 'Greens', 'ar' => 'خضار'],
        ];

        foreach ($itemKeys as $index => $itemKey) {
            $component->set("mountedActions.0.data.banners.{$itemKey}.title", $titles[$index]);
            $component->upload(
                "mountedActions.0.data.banners.{$itemKey}.image_path.".fake()->uuid(),
                [UploadedFile::fake()->image("banner-{$index}.jpg")],
            );
        }

        $component->callMountedAction();

        $banners = HomeBanner::query()->orderBy('id')->get();

        $this->assertCount(2, $banners);

        foreach ($banners as $banner) {
            $this->assertNotNull($banner->image_path);
            $this->assertStringStartsWith('home-banners/', str_replace('\\', '/', (string) $banner->image_path));
            $this->assertTrue(Storage::disk('public')->exists($banner->image_path));
            $this->assertNotNull($banner->displayImageUrl());
        }

        $html = view('store.partials.banners', ['banners' => $banners])->render();

        foreach ($banners as $banner) {
            $this->assertStringContainsString($banner->displayImageUrl(), $html);
        }

        $edit = Livewire::actingAs($admin)->test(EditHomeBanner::class, [
            'record' => $banners[0]->getRouteKey(),
        ]);

        $this->assertNotEmpty($edit->get('data.image_path'));
    }

    public function test_repeater_upload_map_is_stored_on_the_banner(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('livewire-tmp/banner.jpg', 'image-bytes');

        $attributes = HomeBannerAttributes::fromFormRow([
            'title' => ['en' => 'Mango', 'ar' => 'مانجو'],
            'image_path' => [
                '9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d' => 'livewire-tmp/banner.jpg',
            ],
        ], 1);

        $this->assertIsString($attributes['image_path']);
        $this->assertStringStartsWith('home-banners/', $attributes['image_path']);
        $this->assertTrue(Storage::disk('public')->exists($attributes['image_path']));
    }
}
