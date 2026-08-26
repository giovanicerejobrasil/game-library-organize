<?php

declare(strict_types=1);

use App\Enums\GameStatus;
use App\Livewire\Dashboard;
use App\Models\Game;
use App\Models\GameLibrary;
use App\Models\Platform;
use App\Models\User;
use App\Models\UserGame;
use Livewire\Livewire;

test('guest users are redirected to login when accessing dashboard', function () {
    $response = $this->get(route('dashboard'));

    $response->assertRedirect(route('login'));
});

test('authenticated users can access the dashboard', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk();
    $response->assertSeeLivewire(Dashboard::class);
    $response->assertSee('Minha Biblioteca');
    $response->assertSee('Total Jogos');
    $response->assertSee('Concluídos');
});

test('dashboard displays correct user library statistics', function () {
    $user = User::factory()->create();

    $game1 = Game::factory()->create(['title' => 'Game Alpha']);
    $game2 = Game::factory()->create(['title' => 'Game Beta']);
    $game3 = Game::factory()->create(['title' => 'Game Gamma']);

    UserGame::create([
        'user_id' => $user->id,
        'game_id' => $game1->id,
        'status' => GameStatus::Finished,
        'hours_played' => 50.0,
        'rating' => 5.0,
    ]);

    UserGame::create([
        'user_id' => $user->id,
        'game_id' => $game2->id,
        'status' => GameStatus::Playing,
        'hours_played' => 20.0,
        'rating' => 4.0,
    ]);

    UserGame::create([
        'user_id' => $user->id,
        'game_id' => $game3->id,
        'status' => GameStatus::Backlog,
        'hours_played' => 0.0,
        'rating' => null,
    ]);

    Livewire::actingAs($user)
        ->test(Dashboard::class)
        ->assertSee('3') // Total games
        ->assertSee('1') // Finished count
        ->assertSee('70') // Total hours (50 + 20)
        ->assertSee('Game Alpha')
        ->assertSee('Game Beta')
        ->assertSee('Game Gamma');
});

test('dashboard can filter games by search term', function () {
    $user = User::factory()->create();

    $game1 = Game::factory()->create(['title' => 'The Witcher 3: Wild Hunt']);
    $game2 = Game::factory()->create(['title' => 'Cyberpunk 2077']);

    UserGame::create(['user_id' => $user->id, 'game_id' => $game1->id, 'status' => GameStatus::Finished]);
    UserGame::create(['user_id' => $user->id, 'game_id' => $game2->id, 'status' => GameStatus::Playing]);

    Livewire::actingAs($user)
        ->test(Dashboard::class)
        ->set('search', 'Witcher')
        ->assertSee('The Witcher 3: Wild Hunt')
        ->assertDontSee('Cyberpunk 2077');
});

test('dashboard can filter games by status', function () {
    $user = User::factory()->create();

    $game1 = Game::factory()->create(['title' => 'Elden Ring']);
    $game2 = Game::factory()->create(['title' => 'Starfield']);

    UserGame::create(['user_id' => $user->id, 'game_id' => $game1->id, 'status' => GameStatus::Finished]);
    UserGame::create(['user_id' => $user->id, 'game_id' => $game2->id, 'status' => GameStatus::Dropped]);

    Livewire::actingAs($user)
        ->test(Dashboard::class)
        ->set('status', 'finished')
        ->assertSee('Elden Ring')
        ->assertDontSee('Starfield');
});

test('dashboard can filter games by platform and library', function () {
    $user = User::factory()->create();

    $platformSwitch = Platform::create(['name' => 'Nintendo Switch', 'slug' => 'nintendo-switch', 'icon' => 'switch']);
    $platformPc = Platform::create(['name' => 'PC', 'slug' => 'pc', 'icon' => 'pc']);
    $librarySteam = GameLibrary::create(['name' => 'Steam', 'slug' => 'steam', 'icon' => 'steam']);

    $gameSwitch = Game::factory()->create(['title' => 'Super Mario Odyssey']);
    $gameSwitch->platforms()->attach($platformSwitch->id);

    $gamePc = Game::factory()->create(['title' => 'Counter-Strike 2']);
    $gamePc->platforms()->attach($platformPc->id);
    $gamePc->libraries()->attach($librarySteam->id);

    UserGame::create(['user_id' => $user->id, 'game_id' => $gameSwitch->id, 'status' => GameStatus::Finished]);
    UserGame::create(['user_id' => $user->id, 'game_id' => $gamePc->id, 'status' => GameStatus::Playing]);

    Livewire::actingAs($user)
        ->test(Dashboard::class)
        ->set('platform', 'nintendo-switch')
        ->assertSee('Super Mario Odyssey')
        ->assertDontSee('Counter-Strike 2')
        ->set('platform', '')
        ->set('library', 'steam')
        ->assertSee('Counter-Strike 2')
        ->assertDontSee('Super Mario Odyssey');
});

test('dashboard can reset filters', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Dashboard::class)
        ->set('search', 'Test')
        ->set('status', 'finished')
        ->set('platform', 'pc')
        ->call('resetFilters')
        ->assertSet('search', '')
        ->assertSet('status', '')
        ->assertSet('platform', '');
});

test('user can update game progress via quick edit modal', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create(['title' => 'Baldur\'s Gate 3']);

    $userGame = UserGame::create([
        'user_id' => $user->id,
        'game_id' => $game->id,
        'status' => GameStatus::Playing,
        'hours_played' => 15.0,
        'rating' => 4.0,
    ]);

    Livewire::actingAs($user)
        ->test(Dashboard::class)
        ->call('openEditModal', $game->id)
        ->assertSet('showEditModal', true)
        ->assertSet('editingGameId', $game->id)
        ->set('editStatus', GameStatus::Finished->value)
        ->set('editHours', 85.5)
        ->set('editRating', 5.0)
        ->set('editReview', 'Jogo lendário!')
        ->call('saveGameProgress')
        ->assertSet('showEditModal', false)
        ->assertDispatched('game-progress-updated');

    $userGame->refresh();
    expect($userGame->status)->toBe(GameStatus::Finished)
        ->and((float) $userGame->hours_played)->toBe(85.5)
        ->and((float) $userGame->rating)->toBe(5.0)
        ->and($userGame->review)->toBe('Jogo lendário!')
        ->and($userGame->finished_at)->not->toBeNull();
});

test('user can quick update status directly', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create(['title' => 'Hades']);

    $userGame = UserGame::create([
        'user_id' => $user->id,
        'game_id' => $game->id,
        'status' => GameStatus::Backlog,
    ]);

    Livewire::actingAs($user)
        ->test(Dashboard::class)
        ->call('quickSetStatus', $game->id, GameStatus::Playing->value)
        ->assertDispatched('game-progress-updated');

    $userGame->refresh();
    expect($userGame->status)->toBe(GameStatus::Playing);
});

test('authenticated user can log out', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect(route('login'));
    $this->assertGuest();
});
