<?php

declare(strict_types=1);

use App\Enums\AgeRating;
use App\Enums\GameStatus;
use App\Livewire\AddGame;
use App\Models\Game;
use App\Models\GameLibrary;
use App\Models\Platform;
use App\Models\User;
use App\Models\UserGame;
use App\Services\Elasticsearch\GameSearchService;
use Livewire\Livewire;

test('guest users are redirected to login when visiting edit game page', function () {
    $game = Game::factory()->create(['slug' => 'super-mario-odyssey']);

    $response = $this->get(route('games.edit', $game->slug));

    $response->assertRedirect(route('login'));
});

test('game detail page contains link to edit game', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create([
        'title' => 'The Legend of Zelda: Tears of the Kingdom',
        'slug' => 'zelda-totk',
    ]);

    $response = $this->actingAs($user)->get(route('games.show', $game->slug));

    $response->assertOk();
    $response->assertSee(route('games.edit', $game->slug));
    $response->assertSee('Editar Jogo');
});

test('authenticated user can access edit game page by slug with all game details preloaded', function () {
    $user = User::factory()->create();

    $platform = Platform::create(['name' => 'Nintendo Switch', 'slug' => 'nintendo-switch', 'icon' => 'switch']);
    $library = GameLibrary::create(['name' => 'Steam', 'slug' => 'steam', 'icon' => 'steam']);

    $game = Game::factory()->create([
        'title' => 'Chrono Trigger: Definitive Edition',
        'slug' => 'chrono-trigger-definitive',
        'developer' => 'Square Enix',
        'publisher' => 'Square',
        'release_year' => 1995,
        'age_rating' => AgeRating::Age12,
        'synopsis' => 'Uma viagem épica pelo tempo.',
        'genre' => ['RPG', 'Aventura'],
        'purchase_links' => [
            'steam' => 'https://store.steampowered.com/app/613830/CHRONO_TRIGGER/',
        ],
    ]);

    $game->platforms()->attach($platform->id);
    $game->libraries()->attach($library->id);

    UserGame::create([
        'user_id' => $user->id,
        'game_id' => $game->id,
        'status' => GameStatus::Finished,
        'hours_played' => 35.5,
        'rating' => 5.0,
        'review' => 'Uma das maiores obras-primas da história dos games.',
    ]);

    $response = $this->actingAs($user)->get(route('games.edit', $game->slug));

    $response->assertOk();
    $response->assertSeeLivewire(AddGame::class);
    $response->assertSee('Editar Informações do Jogo');
    $response->assertSee('Salvar Alterações');

    Livewire::actingAs($user)
        ->test(AddGame::class, ['game' => $game])
        ->assertSet('existing_game_id', $game->id)
        ->assertSet('title', 'Chrono Trigger: Definitive Edition')
        ->assertSet('developer', 'Square Enix')
        ->assertSet('publisher', 'Square')
        ->assertSet('release_year', 1995)
        ->assertSet('age_rating', '12+')
        ->assertSet('synopsis', 'Uma viagem épica pelo tempo.')
        ->assertSet('selected_genres', ['RPG', 'Aventura'])
        ->assertSet('selected_platforms', [$platform->id])
        ->assertSet('status', GameStatus::Finished->value)
        ->assertSet('hours_played', 35.5)
        ->assertSet('rating', 5.0)
        ->assertSet('review', 'Uma das maiores obras-primas da história dos games.')
        ->assertCount('purchase_links', 1);
});

test('editing game updates information in database, syncs relationships, reindexes in elasticsearch and redirects to game detail', function () {
    $user = User::factory()->create();

    $platform1 = Platform::create(['name' => 'PC', 'slug' => 'pc', 'icon' => 'pc']);
    $platform2 = Platform::create(['name' => 'PlayStation 5', 'slug' => 'playstation-sony', 'icon' => 'playstation']);
    $library1 = GameLibrary::create(['name' => 'Steam', 'slug' => 'steam', 'icon' => 'steam']);

    $game = Game::factory()->create([
        'title' => 'Hades Original',
        'slug' => 'hades-original',
        'developer' => 'Supergiant Games',
        'publisher' => 'Supergiant Games',
        'release_year' => 2020,
        'age_rating' => AgeRating::Age14,
        'synopsis' => 'Sinopse inicial.',
        'genre' => ['Roguelike'],
    ]);

    $game->platforms()->attach($platform1->id);
    $game->libraries()->attach($library1->id);

    // Mock Elasticsearch indexGame call
    $mockSearchService = Mockery::mock(GameSearchService::class);
    $mockSearchService->shouldReceive('indexGame')->once()->andReturn(true);
    $this->app->instance(GameSearchService::class, $mockSearchService);

    Livewire::actingAs($user)
        ->test(AddGame::class, ['game' => $game])
        ->set('title', 'Hades: Game of the Year Edition')
        ->set('synopsis', 'Sinopse atualizada e completa.')
        ->set('release_year', 2021)
        ->set('age_rating', '16+')
        ->set('selected_genres', ['Roguelike', 'Ação', 'Mitologia'])
        ->set('selected_platforms', [$platform1->id, $platform2->id])
        ->set('status', GameStatus::Playing->value)
        ->set('hours_played', 42.0)
        ->set('rating', 4.8)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('games.show', $game->slug));

    // Verifica alterações no banco de dados (PostgreSQL)
    $updatedGame = Game::find($game->id);
    expect($updatedGame->title)->toBe('Hades: Game of the Year Edition')
        ->and($updatedGame->synopsis)->toBe('Sinopse atualizada e completa.')
        ->and($updatedGame->release_year)->toBe(2021)
        ->and($updatedGame->age_rating)->toBe(AgeRating::Age16)
        ->and($updatedGame->genre)->toEqual(['Roguelike', 'Ação', 'Mitologia'])
        ->and($updatedGame->platforms)->toHaveCount(2);

    // Verifica alterações no progresso do usuário (UserGame)
    $userGame = UserGame::where('user_id', $user->id)->where('game_id', $game->id)->first();
    expect($userGame)->not->toBeNull()
        ->and($userGame->status)->toBe(GameStatus::Playing)
        ->and((float) $userGame->hours_played)->toBe(42.0)
        ->and((float) $userGame->rating)->toBe(4.8);
});
