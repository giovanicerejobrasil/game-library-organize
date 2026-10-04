<?php

declare(strict_types=1);

use App\Enums\GameStatus;
use App\Livewire\Settings;
use App\Models\Game;
use App\Models\GameLibrary;
use App\Models\Genre;
use App\Models\Platform;
use App\Models\User;
use App\Models\UserGame;
use App\Services\Settings\SettingsService;
use Livewire\Livewire;

test('admin can view infrastructure diagnostics, application environment, and stack', function () {
    $admin = User::factory()->admin()->create();

    Livewire::actingAs($admin)
        ->test(Settings::class)
        ->set('tab', 'system')
        ->assertSee('Diagnóstico da Infraestrutura')
        ->assertSee('Banco de Dados Relacional')
        ->assertSee('PostgreSQL')
        ->assertSee('Engine de Busca')
        ->assertSee('Elasticsearch 8.x')
        ->assertSee('Ambiente da Aplicação')
        ->assertSee('PHP Version')
        ->assertSee('Laravel Version')
        ->assertSee('Métricas Globais do Catálogo')
        ->assertSee('Jogos no Catálogo')
        ->assertSee('Plataformas')
        ->assertSee('Bibliotecas Digitais')
        ->assertSee('Gêneros');
});

test('regular user cannot view infrastructure diagnostics or application environment or stack', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->set('tab', 'system')
        ->assertSee('Métricas do Catálogo')
        ->assertDontSee('Diagnóstico da Infraestrutura')
        ->assertDontSee('Banco de Dados Relacional')
        ->assertDontSee('PostgreSQL')
        ->assertDontSee('Engine de Busca')
        ->assertDontSee('Elasticsearch 8.x')
        ->assertDontSee('Ambiente da Aplicação')
        ->assertDontSee('PHP Version')
        ->assertDontSee('Laravel Version');
});

test('regular user sees personalized status breakdown when user has library games', function () {
    $user = User::factory()->create();
    $game1 = Game::factory()->create();
    $game2 = Game::factory()->create();
    $game3 = Game::factory()->create();
    $game4 = Game::factory()->create();

    UserGame::create([
        'user_id' => $user->id,
        'game_id' => $game1->id,
        'status' => GameStatus::Playing,
    ]);

    UserGame::create([
        'user_id' => $user->id,
        'game_id' => $game2->id,
        'status' => GameStatus::Finished,
    ]);

    UserGame::create([
        'user_id' => $user->id,
        'game_id' => $game3->id,
        'status' => GameStatus::Backlog,
    ]);

    UserGame::create([
        'user_id' => $user->id,
        'game_id' => $game4->id,
        'status' => GameStatus::Dropped,
    ]);

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->set('tab', 'system')
        ->assertSee('Status da Minha Coleção')
        ->assertSee('Jogando')
        ->assertSee('Finalizados')
        ->assertSee('Backlog')
        ->assertSee('Desistidos');

    $diagnostics = app(SettingsService::class)->getSystemDiagnostics($user);

    expect($diagnostics['metrics']['user_library_games'])->toBe(4)
        ->and($diagnostics['metrics']['playing_games'])->toBe(1)
        ->and($diagnostics['metrics']['finished_games'])->toBe(1)
        ->and($diagnostics['metrics']['backlog_games'])->toBe(1)
        ->and($diagnostics['metrics']['dropped_games'])->toBe(1);
});

test('settings service protects information disclosure for regular users', function () {
    $regularUser = User::factory()->create();
    $otherUser = User::factory()->create();

    // Create system platform and other user custom platform
    Platform::factory()->create(['name' => 'PC Master', 'is_custom' => false]);
    Platform::factory()->create(['name' => 'Secret Other Platform', 'is_custom' => true, 'user_id' => $otherUser->id]);

    // Create system library and other user custom library
    GameLibrary::factory()->create(['name' => 'Steam Public', 'is_custom' => false]);
    GameLibrary::factory()->create(['name' => 'Other Custom Lib', 'is_custom' => true, 'user_id' => $otherUser->id]);

    // Create system genre and other user custom genre
    Genre::factory()->create(['name' => 'RPG Public', 'is_custom' => false]);
    Genre::factory()->create(['name' => 'Other Custom Genre', 'is_custom' => true, 'user_id' => $otherUser->id]);

    $service = app(SettingsService::class);
    $diagnostics = $service->getSystemDiagnostics($regularUser);

    expect($diagnostics['database'])->toBeNull()
        ->and($diagnostics['elasticsearch'])->toBeNull()
        ->and($diagnostics['stack'])->toBeNull()
        ->and(array_key_exists('total_users', $diagnostics['metrics']))->toBeFalse()
        ->and(array_key_exists('total_user_games', $diagnostics['metrics']))->toBeFalse();

    // Should only count system items, not other user's private custom items
    expect($diagnostics['metrics']['total_platforms'])->toBe(1)
        ->and($diagnostics['metrics']['total_libraries'])->toBe(1)
        ->and($diagnostics['metrics']['total_genres'])->toBe(1);
});

test('settings service returns full infrastructure diagnostics for admin users', function () {
    $admin = User::factory()->admin()->create();

    $service = app(SettingsService::class);
    $diagnostics = $service->getSystemDiagnostics($admin);

    expect($diagnostics['database'])->not->toBeNull()
        ->and($diagnostics['database']['connected'])->toBeTrue()
        ->and($diagnostics['database']['driver'])->not->toBeEmpty()
        ->and($diagnostics['elasticsearch'])->not->toBeNull()
        ->and($diagnostics['stack'])->not->toBeNull()
        ->and($diagnostics['stack']['php_version'])->toBe(PHP_VERSION)
        ->and($diagnostics['stack']['laravel_version'])->toBe(app()->version())
        ->and(array_key_exists('total_users', $diagnostics['metrics']))->toBeTrue()
        ->and(array_key_exists('total_user_games', $diagnostics['metrics']))->toBeTrue();
});
