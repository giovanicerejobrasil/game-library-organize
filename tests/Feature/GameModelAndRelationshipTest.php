<?php

declare(strict_types=1);

use App\Enums\GameStatus;
use App\Models\Game;
use App\Models\GameLibrary;
use App\Models\Platform;
use App\Models\User;
use App\Models\UserGame;

test('can create game with platforms, libraries and user progress', function () {
    $user = User::factory()->create();
    $platform = Platform::factory()->create(['name' => 'Nintendo Switch', 'slug' => 'nintendo-switch']);
    $library = GameLibrary::factory()->create(['name' => 'Steam', 'slug' => 'steam']);

    $game = Game::factory()->create([
        'title' => 'The Legend of Zelda: Breath of the Wild',
        'slug' => 'the-legend-of-zelda-breath-of-the-wild',
        'release_year' => 2017,
        'developer' => 'Nintendo EPD',
        'publisher' => 'Nintendo',
        'genre' => ['Action-Adventure', 'Open World'],
    ]);

    $game->platforms()->attach($platform->id);
    $game->libraries()->attach($library->id);

    $userGame = UserGame::create([
        'user_id' => $user->id,
        'game_id' => $game->id,
        'status' => GameStatus::Finished,
        'hours_played' => 120.50,
        'rating' => 5.0,
        'review' => 'Obra-prima atemporal!',
        'finished_at' => now(),
    ]);

    expect($game->platforms)->toHaveCount(1)
        ->and($game->platforms->first()->name)->toBe('Nintendo Switch')
        ->and($game->libraries)->toHaveCount(1)
        ->and($game->libraries->first()->name)->toBe('Steam')
        ->and($user->games)->toHaveCount(1)
        ->and($user->userGames)->toHaveCount(1)
        ->and($userGame->status)->toBe(GameStatus::Finished)
        ->and($userGame->hours_played)->toBe('120.50')
        ->and($userGame->rating)->toBe('5.0');
});
