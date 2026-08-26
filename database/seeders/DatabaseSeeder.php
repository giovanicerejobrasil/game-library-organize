<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (User::count() === 0) {
            User::factory()->create([
                'name' => 'Giovani Cerejo Brasil',
                'email' => 'giovani@example.com',
                'password' => '123456789',
                'theme' => 'dark',
                'brand_primary' => '#182075',
                'brand_secondary' => '#751919',
            ]);
        }

        $this->call([
            PlatformSeeder::class,
            GameLibrarySeeder::class,
            GameSeeder::class,
            UserGameSeeder::class,
        ]);
    }
}
