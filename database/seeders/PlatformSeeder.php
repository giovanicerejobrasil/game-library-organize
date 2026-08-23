<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $platforms = [
            ['name' => 'Nintendo Switch', 'slug' => 'nintendo-switch', 'icon' => 'switch'],
            ['name' => 'Xbox (Microsoft)', 'slug' => 'xbox-microsoft', 'icon' => 'xbox'],
            ['name' => 'PlayStation (Sony)', 'slug' => 'playstation-sony', 'icon' => 'playstation'],
            ['name' => 'PC', 'slug' => 'pc', 'icon' => 'pc'],
        ];

        foreach ($platforms as $platform) {
            Platform::updateOrCreate(
                ['slug' => $platform['slug']],
                [
                    'name' => $platform['name'],
                    'icon' => $platform['icon'],
                    'is_custom' => false,
                    'user_id' => null,
                ]
            );
        }
    }
}
