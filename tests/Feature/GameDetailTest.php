<?php

declare(strict_types=1);

use App\Enums\GameStatus;
use App\Livewire\GameDetail;
use App\Models\Game;
use App\Models\GameLibrary;
use App\Models\Platform;
use App\Models\User;
use App\Models\UserGame;
use App\Services\Elasticsearch\GameSearchService;
use Livewire\Livewire;

test('guest users are redirected to login when visiting game detail page', function () {
    $game = Game::factory()->create(['slug' => 'chrono-trigger']);

    $response = $this->get(route('games.show', $game->slug));

    $response->assertRedirect(route('login'));
});

test('authenticated users can view game details with all global information', function () {
    $user = User::factory()->create();

    $platform = Platform::create(['name' => 'Super Nintendo', 'slug' => 'snes', 'icon' => 'snes']);
    $library = GameLibrary::create(['name' => 'Steam', 'slug' => 'steam', 'icon' => 'steam']);

    $game = Game::factory()->create([
        'title' => 'Chrono Trigger: Definitive',
        'slug' => 'chrono-trigger-definitive',
        'developer' => 'Square Enix',
        'publisher' => 'Square',
        'release_year' => 1995,
        'age_rating' => '12+',
        'is_franchise' => true,
        'franchise_name' => 'Chrono',
        'synopsis' => 'Uma viagem épica pelo tempo...',
        'trailer_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        'genre' => ['RPG', 'Aventura'],
        'purchase_links' => [
            'steam' => 'https://store.steampowered.com/app/613830/CHRONO_TRIGGER/',
        ],
    ]);

    $game->platforms()->attach($platform->id);
    $game->libraries()->attach($library->id);

    $response = $this->actingAs($user)->get(route('games.show', $game->slug));

    $response->assertOk();
    $response->assertSeeLivewire(GameDetail::class);
    $response->assertSee('Chrono Trigger: Definitive');
    $response->assertSee('Square Enix');
    $response->assertSee('Square');
    $response->assertSee('1995');
    $response->assertSee('12+');
    $response->assertSee('Franquia Chrono');
    $response->assertSee('Uma viagem épica pelo tempo...');
    $response->assertSee('Super Nintendo');
    $response->assertSee('Steam');
    $response->assertSee('https://store.steampowered.com/app/613830/CHRONO_TRIGGER/');
    $response->assertSee('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ');
    $response->assertSee('Minha Experiência com o Jogo');
    $response->assertSee('Voltar para Minha Biblioteca');
});

test('authenticated user sees their existing game status, hours, rating, and review', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create(['title' => 'Hollow Knight']);

    UserGame::create([
        'user_id' => $user->id,
        'game_id' => $game->id,
        'status' => GameStatus::Finished,
        'hours_played' => 45.5,
        'rating' => 5.0,
        'review' => 'Obra de arte dos metroidvanias modernos.',
    ]);

    Livewire::actingAs($user)
        ->test(GameDetail::class, ['game' => $game])
        ->assertSet('inUserLibrary', true)
        ->assertSet('status', GameStatus::Finished->value)
        ->assertSet('hours_played', 45.5)
        ->assertSet('rating', 5.0)
        ->assertSet('review', 'Obra de arte dos metroidvanias modernos.')
        ->assertSee('Na sua biblioteca')
        ->assertSee('Salvar Alterações');
});

test('authenticated user can update their personal game progress and rating', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create(['title' => 'Celeste']);

    $mockSearchService = Mockery::mock(GameSearchService::class);
    $mockSearchService->shouldReceive('indexGame')->once()->andReturn(true);
    $this->app->instance(GameSearchService::class, $mockSearchService);

    Livewire::actingAs($user)
        ->test(GameDetail::class, ['game' => $game])
        ->set('status', GameStatus::Playing->value)
        ->set('hours_played', 12.5)
        ->call('setRating', 4.5)
        ->set('review', 'Trilha sonora incrível e gameplay preciso.')
        ->call('saveUserProgress')
        ->assertHasNoErrors()
        ->assertSet('inUserLibrary', true)
        ->assertSet('rating', 4.5);

    $userGame = UserGame::where('user_id', $user->id)->where('game_id', $game->id)->first();
    expect($userGame)->not->toBeNull()
        ->and($userGame->status)->toBe(GameStatus::Playing)
        ->and((float) $userGame->hours_played)->toBe(12.5)
        ->and((float) $userGame->rating)->toBe(4.5)
        ->and($userGame->review)->toBe('Trilha sonora incrível e gameplay preciso.');
});

test('authenticated user can remove game from their personal library', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create(['title' => 'Elden Ring']);

    UserGame::create([
        'user_id' => $user->id,
        'game_id' => $game->id,
        'status' => GameStatus::Playing,
        'hours_played' => 10.0,
    ]);

    $mockSearchService = Mockery::mock(GameSearchService::class);
    $mockSearchService->shouldReceive('indexGame')->once()->andReturn(true);
    $this->app->instance(GameSearchService::class, $mockSearchService);

    Livewire::actingAs($user)
        ->test(GameDetail::class, ['game' => $game])
        ->assertSet('inUserLibrary', true)
        ->call('removeFromLibrary')
        ->assertSet('inUserLibrary', false)
        ->assertSet('status', GameStatus::Backlog->value)
        ->assertSet('hours_played', 0);

    expect(UserGame::where('user_id', $user->id)->where('game_id', $game->id)->exists())->toBeFalse();
});

test('game detail resolves by both slug and numeric id', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create([
        'title' => 'Persona 5 Royal',
        'slug' => 'persona-5-royal',
    ]);

    // Resolução por slug via rota HTTP
    $this->actingAs($user)
        ->get(route('games.show', 'persona-5-royal'))
        ->assertOk()
        ->assertSee('Persona 5 Royal');

    // Resolução por ID numérico via rota HTTP
    $this->actingAs($user)
        ->get(route('games.show', $game->id))
        ->assertOk()
        ->assertSee('Persona 5 Royal');
});

test('dashboard cards link to the game detail page', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create([
        'title' => 'Final Fantasy VII Rebirth',
        'slug' => 'ff7-rebirth',
    ]);

    UserGame::create([
        'user_id' => $user->id,
        'game_id' => $game->id,
        'status' => GameStatus::Backlog,
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee(route('games.show', $game->slug));
});
