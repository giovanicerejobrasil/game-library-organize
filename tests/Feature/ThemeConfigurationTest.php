<?php

declare(strict_types=1);

use App\Enums\GameStatus;
use App\Enums\ThemeMode;
use App\Models\User;

test('user has default theme settings and can customize colors', function () {
    $user = User::factory()->create();

    expect($user->theme)->toBe(ThemeMode::Dark)
        ->and($user->brand_primary)->toBe('#182075')
        ->and($user->brand_secondary)->toBe('#751919');

    $user->update([
        'theme' => ThemeMode::Light,
        'brand_primary' => '#2A3B90',
        'brand_secondary' => '#902A2A',
    ]);

    $user->refresh();

    expect($user->theme)->toBe(ThemeMode::Light)
        ->and($user->brand_primary)->toBe('#2A3B90')
        ->and($user->brand_secondary)->toBe('#902A2A');
});

test('game status enum provides valid labels and css tokens', function () {
    expect(GameStatus::Finished->label())->toBe('Finalizado')
        ->and(GameStatus::Playing->label())->toBe('Em Andamento')
        ->and(GameStatus::Dropped->label())->toBe('Desistido')
        ->and(GameStatus::Backlog->label())->toBe('Backlog')
        ->and(GameStatus::Finished->colorHex())->toBe('#2e7d32')
        ->and(GameStatus::Finished->cssVariable())->toBe('var(--status-finished)');
});
