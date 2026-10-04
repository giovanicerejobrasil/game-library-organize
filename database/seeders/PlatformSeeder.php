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
            ['name' => 'Nintendo Switch', 'slug' => 'nintendo-switch', 'icon' => 'switch', 'color' => '#E60012'],
            ['name' => 'Xbox (Microsoft)', 'slug' => 'xbox-microsoft', 'icon' => 'xbox', 'color' => '#107C0F'],
            ['name' => 'PlayStation (Sony)', 'slug' => 'playstation-sony', 'icon' => 'playstation', 'color' => '#003791'],
            ['name' => 'PC', 'slug' => 'pc', 'icon' => 'pc', 'color' => '#1D2C4B'],
        ];

        foreach ($platforms as $platform) {
            Platform::updateOrCreate(
                ['slug' => $platform['slug']],
                [
                    'name' => $platform['name'],
                    'icon' => $platform['icon'],
                    'color' => $platform['color'],
                    'is_custom' => false,
                    'user_id' => null,
                ]
            );
        }
    }
}
