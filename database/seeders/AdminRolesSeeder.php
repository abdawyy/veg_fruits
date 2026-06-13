<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\AdminRolePermissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AdminRolesSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guard = 'web';

        $superAdmin = Role::firstOrCreate([
            'name' => config('filament-shield.super_admin.name', 'super_admin'),
            'guard_name' => $guard,
        ]);
        $superAdmin->syncPermissions(Permission::all());

        $this->seedRole('orders_manager', array_merge(
            AdminRolePermissions::forSubjects('Order'),
            AdminRolePermissions::only([
                'ViewAny:User',
                'View:User',
                'View:SalesOverviewStats',
                'View:OrdersOverTimeChart',
                'View:TopStorePathsChart',
            ]),
        ));

        $this->seedRole('catalog_manager', AdminRolePermissions::forSubjects(
            'Product',
            'Category',
            'ProduceBox',
            'PackagingType',
            'PreparationService',
            'City',
            'Coupon',
        ));

        $this->seedRole('content_manager', AdminRolePermissions::forSubjects(
            'HomeBanner',
            'ContentString',
            'SeoSetting',
        ));

        $this->seedRole('analytics_viewer', AdminRolePermissions::analyticsViews());

        User::query()
            ->where('is_admin', true)
            ->whereDoesntHave('roles')
            ->each(fn (User $user) => $user->assignRole($superAdmin));
    }

    /**
     * @param  list<string>  $permissionNames
     */
    private function seedRole(string $name, array $permissionNames): void
    {
        $role = Role::firstOrCreate([
            'name' => $name,
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($permissionNames);
    }
}
