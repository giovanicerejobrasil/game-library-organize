<?php

declare(strict_types=1);

use App\Enums\AgeRating;
use App\Enums\SystemLibrary;
use App\Enums\SystemPlatform;
use App\Livewire\AddGame;
use App\Models\Game;
use App\Models\GameLibrary;
use App\Models\Platform;
use App\Models\User;
use Livewire\Livewire;

test('AgeRating enum contains correct official Brazilian ClassInd ratings and colors', function () {
    expect(AgeRating::Livre->value)->toBe('Livre')
        ->and(AgeRating::Livre->minimumAge())->toBe(0)
        ->and(AgeRating::Livre->colors()['bg'])->toBe('#338933')
        ->and(AgeRating::Livre->colors()['text'])->toBe('#ffffff');

    expect(AgeRating::Age10->value)->toBe('10+')
        ->and(AgeRating::Age10->minimumAge())->toBe(10)
        ->and(AgeRating::Age10->colors()['bg'])->toBe('#2474B9');

    expect(AgeRating::Age12->value)->toBe('12+')
        ->and(AgeRating::Age12->minimumAge())->toBe(12)
        ->and(AgeRating::Age12->colors()['bg'])->toBe('#FFCC00')
        ->and(AgeRating::Age12->colors()['text'])->toBe('#000000');

    expect(AgeRating::Age14->value)->toBe('14+')
        ->and(AgeRating::Age14->minimumAge())->toBe(14)
        ->and(AgeRating::Age14->colors()['bg'])->toBe('#DB772C');

    expect(AgeRating::Age16->value)->toBe('16+')
        ->and(AgeRating::Age16->minimumAge())->toBe(16)
        ->and(AgeRating::Age16->colors()['bg'])->toBe('#C90000');

    expect(AgeRating::Age18->value)->toBe('18+')
        ->and(AgeRating::Age18->minimumAge())->toBe(18)
        ->and(AgeRating::Age18->colors()['bg'])->toBe('#000000')
        ->and(AgeRating::Age18->colors()['text'])->toBe('#ffffff');
});

test('AgeRating tryFromLenient handles diverse inputs and variations', function () {
    expect(AgeRating::tryFromLenient('livre'))->toBe(AgeRating::Livre)
        ->and(AgeRating::tryFromLenient('L'))->toBe(AgeRating::Livre)
        ->and(AgeRating::tryFromLenient('10'))->toBe(AgeRating::Age10)
        ->and(AgeRating::tryFromLenient('10+'))->toBe(AgeRating::Age10)
        ->and(AgeRating::tryFromLenient('12'))->toBe(AgeRating::Age12)
        ->and(AgeRating::tryFromLenient('14'))->toBe(AgeRating::Age14)
        ->and(AgeRating::tryFromLenient('16'))->toBe(AgeRating::Age16)
        ->and(AgeRating::tryFromLenient('18'))->toBe(AgeRating::Age18)
        ->and(AgeRating::tryFromLenient(null))->toBeNull()
        ->and(AgeRating::tryFromLenient(''))->toBeNull()
        ->and(AgeRating::tryFromLenient('invalid-rating'))->toBeNull();
});

test('Game model automatically casts age_rating column to AgeRating enum', function () {
    $game = Game::factory()->create([
        'age_rating' => '16+',
    ]);

    expect($game->age_rating)->toBeInstanceOf(AgeRating::class)
        ->and($game->age_rating)->toBe(AgeRating::Age16)
        ->and($game->age_rating->minimumAge())->toBe(16)
        ->and($game->age_rating->colors()['bg'])->toBe('#C90000');

    $game->age_rating = AgeRating::Livre;
    $game->save();

    $reloaded = Game::find($game->id);
    expect($reloaded->age_rating)->toBe(AgeRating::Livre)
        ->and($reloaded->age_rating->value)->toBe('Livre');
});

test('SystemPlatform enum resolves standard platforms and brand colors', function () {
    expect(SystemPlatform::Switch->value)->toBe('nintendo-switch')
        ->and(SystemPlatform::Switch->label())->toBe('Nintendo Switch')
        ->and(SystemPlatform::Switch->brandColors()['bg'])->toBe('#E60012');

    expect(SystemPlatform::Xbox->value)->toBe('xbox-microsoft')
        ->and(SystemPlatform::Xbox->brandColors()['bg'])->toBe('#107C0F');

    expect(SystemPlatform::PlayStation->value)->toBe('playstation-sony')
        ->and(SystemPlatform::PlayStation->brandColors()['bg'])->toBe('#003791');

    expect(SystemPlatform::Pc->value)->toBe('pc')
        ->and(SystemPlatform::Pc->brandColors()['bg'])->toBe('#1D2C4B');

    // Test tryFromSlugOrName
    expect(SystemPlatform::tryFromSlugOrName('Nintendo Switch OLED', 'switch'))->toBe(SystemPlatform::Switch)
        ->and(SystemPlatform::tryFromSlugOrName('PlayStation 5', 'ps5'))->toBe(SystemPlatform::PlayStation)
        ->and(SystemPlatform::tryFromSlugOrName('Xbox Series X', 'xbox'))->toBe(SystemPlatform::Xbox)
        ->and(SystemPlatform::tryFromSlugOrName('PC Windows', 'pc'))->toBe(SystemPlatform::Pc);
});

test('Platform model provides system_platform accessor and delegates resolveColors to enum', function () {
    $platform = Platform::factory()->create([
        'name' => 'PlayStation (Sony)',
        'slug' => 'playstation-sony',
    ]);

    expect($platform->system_platform)->toBe(SystemPlatform::PlayStation)
        ->and($platform->brand_colors['bg'])->toBe('#003791');

    $custom = Platform::factory()->create([
        'name' => 'Retrô Arcade 90s',
        'slug' => 'retro-arcade-90s',
        'is_custom' => true,
    ]);

    expect($custom->system_platform)->toBeNull()
        ->and($custom->brand_colors['bg'])->toBe('var(--bg-main)');
});

test('SystemLibrary enum resolves standard digital libraries and exact brand colors', function () {
    expect(SystemLibrary::Steam->brandColors()['bg'])->toBe('#1D2C4B')
        ->and(SystemLibrary::EpicGames->brandColors()['bg'])->toBe('#000000')
        ->and(SystemLibrary::AmazonLuna->brandColors()['bg'])->toBe('#8E45F7')
        ->and(SystemLibrary::EaApp->brandColors()['bg'])->toBe('#FF4747')
        ->and(SystemLibrary::Gog->brandColors()['bg'])->toBe('#981EEA')
        ->and(SystemLibrary::Rockstar->brandColors()['bg'])->toBe('#F7A600')
        ->and(SystemLibrary::UbisoftConnect->brandColors()['bg'])->toBe('#3B4984')
        ->and(SystemLibrary::XboxPc->brandColors()['bg'])->toBe('#107C0F');

    // Test tryFromSlugOrName
    expect(SystemLibrary::tryFromSlugOrName('Steam Store', 'steam'))->toBe(SystemLibrary::Steam)
        ->and(SystemLibrary::tryFromSlugOrName('Epic Games Launcher', 'epic-games'))->toBe(SystemLibrary::EpicGames)
        ->and(SystemLibrary::tryFromSlugOrName('Amazon Luna', 'amazon-games-luna'))->toBe(SystemLibrary::AmazonLuna)
        ->and(SystemLibrary::tryFromSlugOrName('EA App', 'ea-app'))->toBe(SystemLibrary::EaApp)
        ->and(SystemLibrary::tryFromSlugOrName('GOG Galaxy', 'gog'))->toBe(SystemLibrary::Gog)
        ->and(SystemLibrary::tryFromSlugOrName('Rockstar Games Launcher', 'rockstar-launcher'))->toBe(SystemLibrary::Rockstar)
        ->and(SystemLibrary::tryFromSlugOrName('Ubisoft Connect', 'ubisoft-connect'))->toBe(SystemLibrary::UbisoftConnect)
        ->and(SystemLibrary::tryFromSlugOrName('Xbox PC App', 'xbox-pc'))->toBe(SystemLibrary::XboxPc);
});

test('GameLibrary model provides system_library accessor and delegates resolveColors to enum', function () {
    $library = GameLibrary::factory()->create([
        'name' => 'Rockstar Launcher',
        'slug' => 'rockstar-launcher',
    ]);

    expect($library->system_library)->toBe(SystemLibrary::Rockstar)
        ->and($library->brand_colors['bg'])->toBe('#F7A600');

    $custom = GameLibrary::factory()->create([
        'name' => 'Emulador Particular',
        'slug' => 'emulador-particular',
        'is_custom' => true,
    ]);

    expect($custom->system_library)->toBeNull()
        ->and($custom->brand_colors['bg'])->toBe('var(--bg-main)');
});

test('AddGame form validates age_rating against AgeRating enum', function () {
    $user = User::factory()->create();

    // Invalid enum value fails validation
    Livewire::actingAs($user)
        ->test(AddGame::class)
        ->set('title', 'Jogo Teste')
        ->set('age_rating', 'invalid-rating-value')
        ->call('save')
        ->assertHasErrors(['age_rating']);

    // Valid enum value passes validation
    Livewire::actingAs($user)
        ->test(AddGame::class)
        ->set('title', 'Jogo Válido')
        ->set('age_rating', '18+')
        ->call('save')
        ->assertHasNoErrors(['age_rating']);

    // Null / empty age_rating passes validation (nullable)
    Livewire::actingAs($user)
        ->test(AddGame::class)
        ->set('title', 'Jogo Sem Classificacao')
        ->set('age_rating', '')
        ->call('save')
        ->assertHasNoErrors(['age_rating']);
});
