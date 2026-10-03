<?php

declare(strict_types=1);

use App\Enums\ThemeMode;
use App\Livewire\Settings;
use App\Models\User;
use App\Services\Settings\SettingsService;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

test('guest users are redirected to login when accessing settings', function () {
    $response = $this->get(route('settings'));

    $response->assertRedirect(route('login'));
});

test('authenticated users can access the settings page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('settings'));

    $response->assertOk();
    $response->assertSeeLivewire(Settings::class);
    $response->assertSee('Configurações do Usuário');
    $response->assertSee('Informações da Conta');
    $response->assertSee('Cores do Sistema');
    $response->assertSee('Informações do Sistema');
});

test('user can switch tabs between account, colors, and system', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->assertSet('tab', 'account')
        ->assertSee('Dados Cadastrais')
        ->call('setTab', 'colors')
        ->assertSet('tab', 'colors')
        ->assertSee('Modo do Tema')
        ->assertSee('Paletas Pré-definidas')
        ->call('setTab', 'system')
        ->assertSet('tab', 'system')
        ->assertSee('Diagnóstico da Infraestrutura')
        ->assertSee('Banco de Dados Relacional');
});

test('user can update profile name and email', function () {
    $user = User::factory()->create([
        'name' => 'Original Name',
        'email' => 'original@example.com',
    ]);

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->set('name', 'Novo Nome do Usuário')
        ->set('email', 'novonome@example.com')
        ->call('updateProfile')
        ->assertHasNoErrors();

    $user->refresh();

    expect($user->name)->toBe('Novo Nome do Usuário')
        ->and($user->email)->toBe('novonome@example.com');
});

test('user cannot update email to an already taken email', function () {
    User::factory()->create(['email' => 'taken@example.com']);

    $user = User::factory()->create([
        'name' => 'My Name',
        'email' => 'my@example.com',
    ]);

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->set('email', 'taken@example.com')
        ->call('updateProfile')
        ->assertHasErrors(['email' => 'unique']);

    $user->refresh();
    expect($user->email)->toBe('my@example.com');
});

test('user can change password with correct current password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old-secure-password'),
    ]);

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->set('current_password', 'old-secure-password')
        ->set('new_password', 'new-secure-password')
        ->set('new_password_confirmation', 'new-secure-password')
        ->call('updatePassword')
        ->assertHasNoErrors();

    $user->refresh();

    expect(Hash::check('new-secure-password', $user->password))->toBeTrue();
});

test('user cannot change password with incorrect current password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('correct-password'),
    ]);

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->set('current_password', 'wrong-password')
        ->set('new_password', 'new-secure-password')
        ->set('new_password_confirmation', 'new-secure-password')
        ->call('updatePassword')
        ->assertHasErrors(['current_password']);

    $user->refresh();

    expect(Hash::check('correct-password', $user->password))->toBeTrue();
});

test('user can customize theme and system colors', function () {
    $user = User::factory()->create([
        'theme' => ThemeMode::Dark,
        'brand_primary' => '#182075',
        'brand_secondary' => '#751919',
    ]);

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->set('tab', 'colors')
        ->set('theme', 'light')
        ->set('brand_primary', '#00f0ff')
        ->set('brand_secondary', '#ff003c')
        ->call('saveColors')
        ->assertHasNoErrors()
        ->assertDispatched('glo-colors-applied');

    $user->refresh();

    expect($user->theme)->toBe(ThemeMode::Light)
        ->and($user->brand_primary)->toBe('#00f0ff')
        ->and($user->brand_secondary)->toBe('#ff003c');
});

test('user can apply color presets and reset to default system colors', function () {
    $user = User::factory()->create([
        'theme' => ThemeMode::Light,
        'brand_primary' => '#123456',
        'brand_secondary' => '#654321',
    ]);

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->set('tab', 'colors')
        ->call('applyPreset', '#8b5cf6', '#ec4899')
        ->assertSet('brand_primary', '#8b5cf6')
        ->assertSet('brand_secondary', '#ec4899')
        ->assertDispatched('glo-colors-preview')
        ->call('resetColors')
        ->assertSet('theme', ThemeMode::Dark->value)
        ->assertSet('brand_primary', SettingsService::DEFAULT_BRAND_PRIMARY)
        ->assertSet('brand_secondary', SettingsService::DEFAULT_BRAND_SECONDARY)
        ->assertDispatched('glo-colors-applied');

    $user->refresh();

    expect($user->theme)->toBe(ThemeMode::Dark)
        ->and($user->brand_primary)->toBe(SettingsService::DEFAULT_BRAND_PRIMARY)
        ->and($user->brand_secondary)->toBe(SettingsService::DEFAULT_BRAND_SECONDARY);
});

test('system diagnostics tab retrieves valid database and stack metrics', function () {
    $user = User::factory()->create();

    $test = Livewire::actingAs($user)
        ->test(Settings::class)
        ->set('tab', 'system');

    $diagnostics = app(SettingsService::class)->getSystemDiagnostics($user);

    expect($diagnostics)->toHaveKeys(['database', 'elasticsearch', 'stack', 'metrics'])
        ->and($diagnostics['database']['connected'])->toBeTrue()
        ->and($diagnostics['stack']['php_version'])->toBe(PHP_VERSION)
        ->and($diagnostics['metrics']['user_library_games'])->toBe(0);
});
