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
            ['name' => 'Steam', 'slug' => 'steam', 'icon' => 'steam', 'color' => '#1D2C4B'],
            ['name' => 'Epic Games', 'slug' => 'epic-games', 'icon' => 'epic', 'color' => '#000000'],
            ['name' => 'GOG', 'slug' => 'gog', 'icon' => 'gog', 'color' => '#981EEA'],
            ['name' => 'Ubisoft Connect', 'slug' => 'ubisoft-connect', 'icon' => 'ubisoft', 'color' => '#3B4984'],
            ['name' => 'EA App', 'slug' => 'ea-app', 'icon' => 'ea', 'color' => '#FF4747'],
            ['name' => 'Rockstar Launcher', 'slug' => 'rockstar-launcher', 'icon' => 'rockstar', 'color' => '#F7A600'],
            ['name' => 'Xbox PC', 'slug' => 'xbox-pc', 'icon' => 'xbox', 'color' => '#107C0F'],
            ['name' => 'Amazon Games / Amazon Luna', 'slug' => 'amazon-games-luna', 'icon' => 'amazon', 'color' => '#8E45F7'],
        ];

        foreach ($libraries as $library) {
            GameLibrary::updateOrCreate(
                ['slug' => $library['slug']],
                [
                    'name' => $library['name'],
                    'icon' => $library['icon'],
                    'color' => $library['color'],
                    'is_custom' => false,
                    'user_id' => null,
                ]
            );
        }
    }
}
