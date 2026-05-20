<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(ProduceCatalogSeeder::class);
        $this->call(ProduceBoxSeeder::class);
        $this->call(CatalogOperationsSeeder::class);
        $this->call(HomeBannerSeeder::class);

        User::query()->updateOrCreate(
            ['email' => 'admin@aldawy.local'],
            [
                'name' => 'AL-DAWY Admin',
                'phone_number' => '01000000000',
                'phone_verified_at' => now(),
                'password' => Hash::make('password'),
                'is_admin' => true,
            ],
        );
    }
}
