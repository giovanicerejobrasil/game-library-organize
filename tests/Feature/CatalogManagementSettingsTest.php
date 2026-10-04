<?php

declare(strict_types=1);

use App\Livewire\Settings;
use App\Models\GameLibrary;
use App\Models\Genre;
use App\Models\Platform;
use App\Models\User;
use App\Services\Games\GameService;
use App\Services\Support\ColorConverter;
use Livewire\Livewire;

test('user sees catalog management button between colors and system in settings page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('settings'));

    $response->assertOk();
    $response->assertSee('Plataformas e Bibliotecas');
});

test('user can switch to catalog tab and view platforms, libraries and genres subtabs', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->call('setTab', 'catalog')
        ->assertSet('tab', 'catalog')
        ->assertSet('catalogSubTab', 'platforms')
        ->assertSee('Gerenciamento do Catálogo')
        ->assertSee('Cadastrar Nova Plataforma')
        ->call('setCatalogSubTab', 'libraries')
        ->assertSet('catalogSubTab', 'libraries')
        ->assertSee('Cadastrar Nova Biblioteca Digital (PC)')
        ->call('setCatalogSubTab', 'genres')
        ->assertSet('catalogSubTab', 'genres')
        ->assertSee('Cadastrar Novo Gênero');
});

test('color converter correctly calculates hex, rgb, and hsl formats', function () {
    $formats = ColorConverter::toAllFormats('#E60012');

    expect($formats['hex'])->toBe('#E60012')
        ->and($formats['rgb'])->toBe('rgb(230, 0, 18)')
        ->and($formats['hsl'])->toBe('hsl(355, 100%, 45%)');

    // Test 3-character hex
    expect(ColorConverter::normalizeHex('#fff'))->toBe('#FFFFFF')
        ->and(ColorConverter::hexToRgb('#fff'))->toBe('rgb(255, 255, 255)');

    // Test validation
    expect(ColorConverter::isValidHex('#182075'))->toBeTrue()
        ->and(ColorConverter::isValidHex('182075'))->toBeTrue()
        ->and(ColorConverter::isValidHex('#xyz123'))->toBeFalse();
});

test('user can create a custom platform with name and color', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->call('setTab', 'catalog')
        ->set('platform_name', 'PlayStation 2')
        ->set('platform_color', '#003791')
        ->call('createPlatform')
        ->assertHasNoErrors()
        ->assertSee('Plataforma cadastrada com sucesso!');

    $platform = Platform::where('name', 'PlayStation 2')->first();
    expect($platform)->not->toBeNull()
        ->and($platform->is_custom)->toBeTrue()
        ->and($platform->user_id)->toBe($user->id)
        ->and($platform->color)->toBe('#003791')
        ->and($platform->slug)->toBe('playstation-2');
});

test('user can create a custom pc library with name and color', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->call('setTab', 'catalog')
        ->call('setCatalogSubTab', 'libraries')
        ->set('library_name', 'itch.io')
        ->set('library_color', '#FA5C5C')
        ->call('createLibrary')
        ->assertHasNoErrors()
        ->assertSee('Biblioteca digital para PC cadastrada com sucesso!');

    $library = GameLibrary::where('name', 'itch.io')->first();
    expect($library)->not->toBeNull()
        ->and($library->is_custom)->toBeTrue()
        ->and($library->user_id)->toBe($user->id)
        ->and($library->color)->toBe('#FA5C5C');
});

test('user can create a custom genre', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->call('setTab', 'catalog')
        ->call('setCatalogSubTab', 'genres')
        ->set('genre_name', 'Soulslike')
        ->call('createGenre')
        ->assertHasNoErrors()
        ->assertSee('Gênero cadastrado com sucesso!');

    $genre = Genre::where('name', 'Soulslike')->first();
    expect($genre)->not->toBeNull()
        ->and($genre->is_custom)->toBeTrue()
        ->and($genre->user_id)->toBe($user->id)
        ->and($genre->slug)->toBe('soulslike');
});

test('regular user only sees system items and their own custom items', function () {
    $userA = User::factory()->create(['name' => 'User A']);
    $userB = User::factory()->create(['name' => 'User B']);

    Platform::create([
        'name' => 'Platform Custom A',
        'slug' => 'platform-custom-a',
        'color' => '#123456',
        'is_custom' => true,
        'user_id' => $userA->id,
    ]);

    GameLibrary::create([
        'name' => 'Library Custom A',
        'slug' => 'library-custom-a',
        'color' => '#654321',
        'is_custom' => true,
        'user_id' => $userA->id,
    ]);

    Genre::create([
        'name' => 'Genre Custom A',
        'slug' => 'genre-custom-a',
        'is_custom' => true,
        'user_id' => $userA->id,
    ]);

    // User B should NOT see user A's custom items
    Livewire::actingAs($userB)
        ->test(Settings::class)
        ->call('setTab', 'catalog')
        ->assertDontSee('Platform Custom A')
        ->call('setCatalogSubTab', 'libraries')
        ->assertDontSee('Library Custom A')
        ->call('setCatalogSubTab', 'genres')
        ->assertDontSee('Genre Custom A');

    // User A SHOULD see user A's custom items
    Livewire::actingAs($userA)
        ->test(Settings::class)
        ->call('setTab', 'catalog')
        ->assertSee('Platform Custom A')
        ->call('setCatalogSubTab', 'libraries')
        ->assertSee('Library Custom A')
        ->call('setCatalogSubTab', 'genres')
        ->assertSee('Genre Custom A');
});

test('regular user can edit and delete only their own custom items', function () {
    $user = User::factory()->create();

    $myPlatform = Platform::create([
        'name' => 'My Dreamcast',
        'slug' => 'my-dreamcast',
        'color' => '#FF6600',
        'is_custom' => true,
        'user_id' => $user->id,
    ]);

    // User can edit their own platform
    Livewire::actingAs($user)
        ->test(Settings::class)
        ->call('setTab', 'catalog')
        ->call('editPlatform', $myPlatform->id)
        ->assertSet('editing_platform_id', $myPlatform->id)
        ->set('edit_platform_name', 'Sega Dreamcast Pro')
        ->set('edit_platform_color', '#FF8800')
        ->call('updatePlatform')
        ->assertHasNoErrors()
        ->assertSee('Plataforma atualizada com sucesso!');

    expect($myPlatform->fresh()->name)->toBe('Sega Dreamcast Pro')
        ->and($myPlatform->fresh()->color)->toBe('#FF8800');

    // User can delete their own platform
    Livewire::actingAs($user)
        ->test(Settings::class)
        ->call('setTab', 'catalog')
        ->call('deletePlatform', $myPlatform->id)
        ->assertSee('Plataforma excluída com sucesso!');

    expect(Platform::find($myPlatform->id))->toBeNull();
});

test('regular user cannot delete or edit system items or other users custom items', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $systemPlatform = Platform::create([
        'name' => 'System Platform',
        'slug' => 'system-platform',
        'color' => '#000000',
        'is_custom' => false,
        'user_id' => null,
    ]);

    $otherUserPlatform = Platform::create([
        'name' => 'Other User Platform',
        'slug' => 'other-user-platform',
        'color' => '#111111',
        'is_custom' => true,
        'user_id' => $otherUser->id,
    ]);

    // Trying to delete system platform
    Livewire::actingAs($user)
        ->test(Settings::class)
        ->call('setTab', 'catalog')
        ->call('deletePlatform', $systemPlatform->id)
        ->assertSee('Você não tem permissão para excluir esta plataforma.');

    expect(Platform::find($systemPlatform->id))->not->toBeNull();

    // Trying to delete other user's platform
    Livewire::actingAs($user)
        ->test(Settings::class)
        ->call('setTab', 'catalog')
        ->call('deletePlatform', $otherUserPlatform->id)
        ->assertSee('Você não tem permissão para excluir esta plataforma.');

    expect(Platform::find($otherUserPlatform->id))->not->toBeNull();
});

test('admin user can edit and delete system items and their own custom items but not other users custom items', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $regularUser = User::factory()->create(['is_admin' => false]);

    $systemPlatform = Platform::create([
        'name' => 'Old System Console',
        'slug' => 'old-system-console',
        'color' => '#112233',
        'is_custom' => false,
        'user_id' => null,
    ]);

    $adminPlatform = Platform::create([
        'name' => 'Admin Custom Platform',
        'slug' => 'admin-custom-platform',
        'color' => '#445566',
        'is_custom' => true,
        'user_id' => $admin->id,
    ]);

    $userPlatform = Platform::create([
        'name' => 'User Personal Platform',
        'slug' => 'user-personal-platform',
        'color' => '#778899',
        'is_custom' => true,
        'user_id' => $regularUser->id,
    ]);

    // Admin CAN delete system item
    Livewire::actingAs($admin)
        ->test(Settings::class)
        ->call('setTab', 'catalog')
        ->call('deletePlatform', $systemPlatform->id)
        ->assertSee('Plataforma excluída com sucesso!');

    expect(Platform::find($systemPlatform->id))->toBeNull();

    // Admin CAN delete admin's own custom item
    Livewire::actingAs($admin)
        ->test(Settings::class)
        ->call('setTab', 'catalog')
        ->call('deletePlatform', $adminPlatform->id)
        ->assertSee('Plataforma excluída com sucesso!');

    expect(Platform::find($adminPlatform->id))->toBeNull();

    // Admin CANNOT delete regular user's personal item
    Livewire::actingAs($admin)
        ->test(Settings::class)
        ->call('setTab', 'catalog')
        ->call('deletePlatform', $userPlatform->id)
        ->assertSee('Você não tem permissão para excluir esta plataforma.');

    expect(Platform::find($userPlatform->id))->not->toBeNull();
});

test('validation prevents creating platform with empty name or invalid color', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->call('setTab', 'catalog')
        ->set('platform_name', '')
        ->set('platform_color', 'invalid-color')
        ->call('createPlatform')
        ->assertHasErrors(['platform_name', 'platform_color']);
});

test('catalog renders interactive color badges for hex rgb and hsl', function () {
    $user = User::factory()->create();

    $platform = Platform::create([
        'name' => 'Custom Console',
        'slug' => 'custom-console',
        'color' => '#182075',
        'is_custom' => true,
        'user_id' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->call('setTab', 'catalog')
        ->assertSee('Custom Console')
        ->assertSee('#182075')
        ->assertSee('rgb(24, 32, 117)')
        ->assertSee('hsl(235, 66%, 28%)')
        ->assertSee('COPIADO!');
});

test('user can edit and delete their custom library', function () {
    $user = User::factory()->create();

    $library = GameLibrary::create([
        'name' => 'Original Library',
        'slug' => 'original-library',
        'color' => '#112233',
        'is_custom' => true,
        'user_id' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->call('setTab', 'catalog')
        ->call('setCatalogSubTab', 'libraries')
        ->call('editLibrary', $library->id)
        ->assertSet('editing_library_id', $library->id)
        ->set('edit_library_name', 'Updated Library')
        ->set('edit_library_color', '#334455')
        ->call('updateLibrary')
        ->assertHasNoErrors()
        ->assertSee('Biblioteca atualizada com sucesso!');

    expect($library->fresh()->name)->toBe('Updated Library')
        ->and($library->fresh()->color)->toBe('#334455');

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->call('setTab', 'catalog')
        ->call('setCatalogSubTab', 'libraries')
        ->call('deleteLibrary', $library->id)
        ->assertSee('Biblioteca excluída com sucesso!');

    expect(GameLibrary::find($library->id))->toBeNull();
});

test('user can edit and delete their custom genre', function () {
    $user = User::factory()->create();

    $genre = Genre::create([
        'name' => 'Original Genre',
        'slug' => 'original-genre',
        'is_custom' => true,
        'user_id' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->call('setTab', 'catalog')
        ->call('setCatalogSubTab', 'genres')
        ->call('editGenre', $genre->id)
        ->assertSet('editing_genre_id', $genre->id)
        ->set('edit_genre_name', 'Updated Genre')
        ->call('updateGenre')
        ->assertHasNoErrors()
        ->assertSee('Gênero atualizado com sucesso!');

    expect($genre->fresh()->name)->toBe('Updated Genre');

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->call('setTab', 'catalog')
        ->call('setCatalogSubTab', 'genres')
        ->call('deleteGenre', $genre->id)
        ->assertSee('Gênero excluído com sucesso!');

    expect(Genre::find($genre->id))->toBeNull();
});

test('game service getAvailableGenres returns custom genres created by user', function () {
    $user = User::factory()->create();

    Genre::create([
        'name' => 'JRPG Custom',
        'slug' => 'jrpg-custom',
        'is_custom' => true,
        'user_id' => $user->id,
    ]);

    $gameService = app(GameService::class);
    $genres = $gameService->getAvailableGenres($user->id);

    expect($genres)->toContain('JRPG Custom');
});
