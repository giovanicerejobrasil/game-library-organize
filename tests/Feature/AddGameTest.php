<?php

declare(strict_types=1);

use App\Enums\GameStatus;
use App\Livewire\AddGame;
use App\Models\Game;
use App\Models\GameLibrary;
use App\Models\Platform;
use App\Models\User;
use App\Models\UserGame;
use App\Services\Elasticsearch\GameSearchService;
use Livewire\Livewire;

test('guest users are redirected to login when visiting add game page', function () {
    $response = $this->get(route('games.create'));

    $response->assertRedirect(route('login'));
});

test('authenticated users can access add game page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('games.create'));

    $response->assertOk();
    $response->assertSeeLivewire(AddGame::class);
    $response->assertSee('Adicionar Jogo à Biblioteca');
    $response->assertSee('Informações Globais do Jogo');
    $response->assertDontSee('Minha Experiência');
});

test('welcome page button points to games.create', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee(route('games.create'));
    $response->assertSee('Adicionar Jogos');
});

test('dashboard page button points to games.create', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee(route('games.create'));
    $response->assertSee('Adicionar Jogo');
});

test('add game form validates required title field', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(AddGame::class)
        ->set('title', '')
        ->call('save')
        ->assertHasErrors(['title' => 'required']);
});

test('add game form validates rating range from 0 to 5', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(AddGame::class)
        ->set('title', 'Super Mario World')
        ->set('rating', 6.0)
        ->call('save')
        ->assertHasErrors(['rating' => 'max']);
});

test('add game form validates negative hours played', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(AddGame::class)
        ->set('title', 'Chrono Trigger')
        ->set('hours_played', -5)
        ->call('save')
        ->assertHasErrors(['hours_played' => 'min']);
});

test('authenticated user can successfully register a new game with all details and user progress', function () {
    $user = User::factory()->create();

    $platform1 = Platform::create(['name' => 'Nintendo Switch', 'slug' => 'nintendo-switch', 'icon' => 'switch']);
    $platform2 = Platform::create(['name' => 'PC', 'slug' => 'pc', 'icon' => 'pc']);
    $library1 = GameLibrary::create(['name' => 'Steam', 'slug' => 'steam', 'icon' => 'steam']);

    Livewire::actingAs($user)
        ->test(AddGame::class)
        ->set('title', 'Metroid Prime Remastered')
        ->set('release_year', 2023)
        ->set('developer', 'Retro Studios')
        ->set('publisher', 'Nintendo')
        ->set('synopsis', 'Na pele da caçadora de recompensas Samus Aran...')
        ->set('cover_image', 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=600')
        ->set('background_image', 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=1920')
        ->set('trailer_url', 'https://www.youtube.com/watch?v=example')
        ->set('is_franchise', true)
        ->set('franchise_name', 'Metroid')
        ->set('age_rating', '12+')
        ->set('selected_genres', ['Ação', 'Aventura', 'Sci-Fi'])
        ->set('selected_platforms', [$platform1->id, $platform2->id])
        ->set('selected_libraries', [$library1->id])
        ->set('status', GameStatus::Finished->value)
        ->set('hours_played', 24.5)
        ->set('rating', 4.9)
        ->set('review', 'Excelente remasterização de um clássico absoluto.')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    // Verifica se o jogo foi criado no banco de dados
    $game = Game::where('title', 'Metroid Prime Remastered')->first();
    expect($game)->not->toBeNull()
        ->and($game->developer)->toBe('Retro Studios')
        ->and($game->release_year)->toBe(2023)
        ->and($game->is_franchise)->toBeTrue()
        ->and($game->franchise_name)->toBe('Metroid');

    // Verifica os relacionamentos pivô
    expect($game->platforms)->toHaveCount(2)
        ->and($game->libraries)->toHaveCount(1)
        ->and($game->libraries->first()->name)->toBe('Steam');

    // Verifica o registro UserGame do usuário
    $userGame = UserGame::where('user_id', $user->id)->where('game_id', $game->id)->first();
    expect($userGame)->not->toBeNull()
        ->and($userGame->status)->toBe(GameStatus::Finished)
        ->and((float) $userGame->hours_played)->toBe(24.5)
        ->and((float) $userGame->rating)->toBe(4.9)
        ->and($userGame->review)->toBe('Excelente remasterização de um clássico absoluto.')
        ->and($userGame->finished_at)->not->toBeNull();
});

test('can select and prefill form with an existing catalog game', function () {
    $user = User::factory()->create();

    $platform = Platform::create(['name' => 'PlayStation 5', 'slug' => 'ps5', 'icon' => 'ps5']);
    $library = GameLibrary::create(['name' => 'PlayStation Network', 'slug' => 'psn', 'icon' => 'psn']);

    $existingGame = Game::factory()->create([
        'title' => 'Demon\'s Souls',
        'developer' => 'Bluepoint Games',
        'release_year' => 2020,
        'synopsis' => 'Um remake fiel e deslumbrante...',
    ]);
    $existingGame->platforms()->attach($platform->id);
    $existingGame->libraries()->attach($library->id);

    Livewire::actingAs($user)
        ->test(AddGame::class)
        ->call('selectCatalogGame', $existingGame->id)
        ->assertSet('title', 'Demon\'s Souls')
        ->assertSet('developer', 'Bluepoint Games')
        ->assertSet('release_year', 2020)
        ->assertSet('existing_game_id', $existingGame->id)
        ->set('status', GameStatus::Playing->value)
        ->set('hours_played', 15.0)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    // Verifica que o jogo não foi duplicado
    expect(Game::where('title', 'Demon\'s Souls')->count())->toBe(1);

    // Verifica que o UserGame foi vinculado ao usuário
    $userGame = UserGame::where('user_id', $user->id)->where('game_id', $existingGame->id)->first();
    expect($userGame)->not->toBeNull()
        ->and($userGame->status)->toBe(GameStatus::Playing)
        ->and((float) $userGame->hours_played)->toBe(15.0);
});

test('saving game indexes it into elasticsearch service', function () {
    $user = User::factory()->create();

    $mockSearchService = Mockery::mock(GameSearchService::class);
    $mockSearchService->shouldReceive('indexGame')
        ->once()
        ->andReturn(true);

    $this->app->instance(GameSearchService::class, $mockSearchService);

    Livewire::actingAs($user)
        ->test(AddGame::class)
        ->set('title', 'Hollow Knight: Silksong')
        ->set('status', GameStatus::Backlog->value)
        ->call('save')
        ->assertHasNoErrors();
});

test('clearing catalog selection resets all form fields to empty', function () {
    $user = User::factory()->create();

    $existingGame = Game::factory()->create([
        'title' => 'Dark Souls III',
        'developer' => 'FromSoftware',
        'release_year' => 2016,
        'synopsis' => 'As fires fade...',
    ]);

    Livewire::actingAs($user)
        ->test(AddGame::class)
        ->call('selectCatalogGame', $existingGame->id)
        ->assertSet('title', 'Dark Souls III')
        ->assertSet('developer', 'FromSoftware')
        ->assertSet('release_year', 2016)
        ->assertSet('existing_game_id', $existingGame->id)
        ->call('clearCatalogSelection')
        ->assertSet('existing_game_id', null)
        ->assertSet('selectedGameTitle', null)
        ->assertSet('title', '')
        ->assertSet('developer', '')
        ->assertSet('publisher', '')
        ->assertSet('release_year', null)
        ->assertSet('synopsis', '')
        ->assertSet('cover_image', '')
        ->assertSet('background_image', '')
        ->assertSet('selected_genres', [])
        ->assertSet('selected_platforms', [])
        ->assertSet('selected_libraries', []);
});

test('experience section is only visible when an existing game is selected', function () {
    $user = User::factory()->create();

    $existingGame = Game::factory()->create([
        'title' => 'Bloodborne',
    ]);

    Livewire::actingAs($user)
        ->test(AddGame::class)
        // Quando nenhum jogo do catálogo está selecionado, não exibe Minha Experiência
        ->assertDontSee('Minha Experiência')
        // Ao selecionar jogo existente, a seção torna-se visível
        ->call('selectCatalogGame', $existingGame->id)
        ->assertSee('Minha Experiência')
        // Ao limpar a seleção, a seção volta a ser ocultada
        ->call('clearCatalogSelection')
        ->assertDontSee('Minha Experiência');
});

test('digital libraries section is activated only when pc platform is selected', function () {
    $user = User::factory()->create();

    $pcPlatform = Platform::create(['name' => 'PC', 'slug' => 'pc', 'icon' => 'pc']);
    $switchPlatform = Platform::create(['name' => 'Nintendo Switch', 'slug' => 'nintendo-switch', 'icon' => 'switch']);
    $library = GameLibrary::create(['name' => 'Steam', 'slug' => 'steam', 'icon' => 'steam']);

    Livewire::actingAs($user)
        ->test(AddGame::class)
        // Sem PC selecionado, bibliotecas digitais não estão ativadas
        ->assertSee('Exclusivo para PC')
        ->assertSee('Selecione a plataforma PC acima para ativar')
        ->assertDontSee('Ativado para PC')
        // Seleciona plataforma que não é PC
        ->set('selected_platforms', [$switchPlatform->id])
        ->assertDontSee('Ativado para PC')
        // Seleciona PC
        ->set('selected_platforms', [$switchPlatform->id, $pcPlatform->id])
        ->assertSee('Ativado para PC')
        ->assertSee('Steam')
        // Pode selecionar a biblioteca Steam
        ->set('selected_libraries', [$library->id])
        ->assertSet('selected_libraries', [$library->id])
        // Se desmarcar PC, as bibliotecas selecionadas são limpas automaticamente
        ->set('selected_platforms', [$switchPlatform->id])
        ->assertSet('selected_libraries', [])
        ->assertDontSee('Ativado para PC')
        // Pode ativar novamente via selectPcPlatform
        ->call('selectPcPlatform')
        ->assertSee('Ativado para PC');
});

test('saving game without pc platform discards any libraries', function () {
    $user = User::factory()->create();

    $switchPlatform = Platform::create(['name' => 'Nintendo Switch', 'slug' => 'nintendo-switch', 'icon' => 'switch']);
    $library = GameLibrary::create(['name' => 'Steam', 'slug' => 'steam', 'icon' => 'steam']);

    Livewire::actingAs($user)
        ->test(AddGame::class)
        ->set('title', 'Super Mario Odyssey')
        ->set('selected_platforms', [$switchPlatform->id])
        ->set('selected_libraries', [$library->id]) // Tentativa de enviar biblioteca sem PC
        ->call('save')
        ->assertHasNoErrors();

    $game = Game::where('title', 'Super Mario Odyssey')->first();
    expect($game)->not->toBeNull()
        ->and($game->platforms)->toHaveCount(1)
        ->and($game->libraries)->toHaveCount(0); // Garante que bibliotecas foram descartadas
});

test('digital library chips and preview render with official brand colors when selected', function () {
    $user = User::factory()->create();

    $pcPlatform = Platform::create(['name' => 'PC', 'slug' => 'pc', 'icon' => 'pc']);
    $steam = GameLibrary::create(['name' => 'Steam', 'slug' => 'steam', 'icon' => 'steam']);
    $epic = GameLibrary::create(['name' => 'Epic Games', 'slug' => 'epic-games', 'icon' => 'epic']);
    $rockstar = GameLibrary::create(['name' => 'Rockstar Launcher', 'slug' => 'rockstar-launcher', 'icon' => 'rockstar']);

    // Verifica que as cores de cada modelo correspondem às especificações oficiais
    expect($steam->brand_colors['bg'])->toBe('#1D2C4B')
        ->and($epic->brand_colors['bg'])->toBe('#000000')
        ->and($rockstar->brand_colors['bg'])->toBe('#F7A600')
        ->and($rockstar->brand_colors['text'])->toBe('#000000');

    Livewire::actingAs($user)
        ->test(AddGame::class)
        ->set('selected_platforms', [$pcPlatform->id])
        // Quando não selecionado, já renderiza a cor oficial no ponto indicador
        ->assertSee('#1D2C4B')
        ->assertSee('#000000')
        ->assertSee('#F7A600')
        // Seleciona Steam e Rockstar
        ->set('selected_libraries', [$steam->id, $rockstar->id])
        // Verifica que o fundo oficial de seleção é aplicado nos chips
        ->assertSee('background-color: #1D2C4B', false)
        ->assertSee('background-color: #F7A600', false)
        // Verifica que a pré-visualização do sidebar exibe os badges das bibliotecas selecionadas
        ->assertSee('Steam')
        ->assertSee('Rockstar Launcher');
});

test('custom genre can be added and appears as a tag with remove button', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(AddGame::class)
        ->set('custom_genre', 'Sobrevivência')
        ->call('addCustomGenre')
        ->assertSet('selected_genres', ['Sobrevivência'])
        ->assertSee('Outros gêneros adicionados:')
        ->assertSee('Sobrevivência')
        ->call('toggleGenre', 'Sobrevivência')
        ->assertSet('selected_genres', [])
        ->assertDontSee('Outros gêneros adicionados:');
});

test('user can set half star ratings like 2.5 and 4.5', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create(['title' => 'Half-Life 2']);

    Livewire::actingAs($user)
        ->test(AddGame::class)
        ->call('selectCatalogGame', $game->id)
        ->call('setRating', 4.5)
        ->assertSet('rating', 4.5)
        ->assertSee('4.5 / 5.0')
        ->call('setRating', 2.5)
        ->assertSet('rating', 2.5)
        ->assertSee('2.5 / 5.0');
});

test('preview sidebar displays platform and genre tags alongside libraries', function () {
    $user = User::factory()->create();

    $xbox = Platform::create(['name' => 'Xbox (Microsoft)', 'slug' => 'xbox', 'icon' => 'xbox']);
    $steam = GameLibrary::create(['name' => 'Steam', 'slug' => 'steam', 'icon' => 'steam']);

    Livewire::actingAs($user)
        ->test(AddGame::class)
        ->set('selected_platforms', [$xbox->id])
        ->set('selected_libraries', [$steam->id])
        ->call('toggleGenre', 'Aventura')
        ->call('toggleGenre', 'Ação')
        ->assertSee('Xbox (Microsoft)')
        ->assertSee('Steam')
        ->assertSee('Aventura')
        ->assertSee('Ação')
        ->assertSee('Tamanho Real');
});
