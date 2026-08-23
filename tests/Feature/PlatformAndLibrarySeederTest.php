<?php

declare(strict_types=1);

use App\Models\GameLibrary;
use App\Models\Platform;
use Database\Seeders\GameLibrarySeeder;
use Database\Seeders\PlatformSeeder;

test('platform seeder populates initial standard platforms correctly', function () {
    $this->seed(PlatformSeeder::class);

    $expectedSlugs = [
        'nintendo-switch',
        'xbox-microsoft',
        'playstation-sony',
        'pc',
    ];

    expect(Platform::count())->toBe(4);

    foreach ($expectedSlugs as $slug) {
        expect(Platform::where('slug', $slug)->exists())->toBeTrue();
    }
});

test('game library seeder populates initial standard digital libraries correctly', function () {
    $this->seed(GameLibrarySeeder::class);

    $expectedSlugs = [
        'steam',
        'epic-games',
        'gog',
        'ubisoft-connect',
        'ea-app',
        'rockstar-launcher',
        'xbox-pc',
        'amazon-games-luna',
    ];

    expect(GameLibrary::count())->toBe(8);

    foreach ($expectedSlugs as $slug) {
        expect(GameLibrary::where('slug', $slug)->exists())->toBeTrue();
    }
});
