<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\GameLibrary;
use Illuminate\Database\Seeder;

class GameLibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $libraries = [
            ['name' => 'Steam', 'slug' => 'steam', 'icon' => 'steam'],
            ['name' => 'Epic Games', 'slug' => 'epic-games', 'icon' => 'epic'],
            ['name' => 'GOG', 'slug' => 'gog', 'icon' => 'gog'],
            ['name' => 'Ubisoft Connect', 'slug' => 'ubisoft-connect', 'icon' => 'ubisoft'],
            ['name' => 'EA App', 'slug' => 'ea-app', 'icon' => 'ea'],
            ['name' => 'Rockstar Launcher', 'slug' => 'rockstar-launcher', 'icon' => 'rockstar'],
            ['name' => 'Xbox PC', 'slug' => 'xbox-pc', 'icon' => 'xbox'],
            ['name' => 'Amazon Games / Amazon Luna', 'slug' => 'amazon-games-luna', 'icon' => 'amazon'],
        ];

        foreach ($libraries as $library) {
            GameLibrary::updateOrCreate(
                ['slug' => $library['slug']],
                [
                    'name' => $library['name'],
                    'icon' => $library['icon'],
                    'is_custom' => false,
                    'user_id' => null,
                ]
            );
        }
    }
}
