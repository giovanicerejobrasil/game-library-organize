<?php

use App\Models\User;

test('register page can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertOk();
    $response->assertViewIs('register');
    $response->assertSee('Crie sua Conta');
    $response->assertSee('Nome Completo');
    $response->assertSee('E-mail');
    $response->assertSee('Senha');
    $response->assertSee('Confirmar Senha');
    $response->assertSee('Criar Minha Conta');
});

test('users can register successfully and are authenticated', function () {
    $userData = [
        'name' => 'Novo Usuário Gamer',
        'email' => 'novouser@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'terms' => '1',
    ];

    $response = $this->post(route('attemptSignUp'), $userData);

    $response->assertRedirect(route('home'));

    $this->assertDatabaseHas('users', [
        'name' => 'Novo Usuário Gamer',
        'email' => 'novouser@example.com',
        'theme' => 'dark',
        'brand_primary' => '#182075',
        'brand_secondary' => '#751919',
    ]);

    $user = User::where('email', 'novouser@example.com')->first();
    $this->assertNotNull($user);
    $this->assertAuthenticatedAs($user);
});

test('registration fails when fields are invalid or missing', function () {
    $response = $this->post(route('attemptSignUp'), [
        'name' => '',
        'email' => 'invalid-email',
        'password' => '',
        'password_confirmation' => '',
    ]);

    $response->assertSessionHasErrors(['name', 'email', 'password', 'terms']);
    $this->assertGuest();
});

test('registration fails when password confirmation does not match', function () {
    $response = $this->post(route('attemptSignUp'), [
        'name' => 'Novo Usuário',
        'email' => 'novouser@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'DifferentPassword123!',
        'terms' => '1',
    ]);

    $response->assertSessionHasErrors(['password']);
    $this->assertGuest();
});

test('registration fails when password is less than 8 characters', function () {
    $response = $this->post(route('attemptSignUp'), [
        'name' => 'Novo Usuário',
        'email' => 'novouser@example.com',
        'password' => 'Pass1!',
        'password_confirmation' => 'Pass1!',
        'terms' => '1',
    ]);

    $response->assertSessionHasErrors(['password']);
    $this->assertGuest();
});

test('registration fails when terms of service are not accepted', function () {
    $response = $this->post(route('attemptSignUp'), [
        'name' => 'Novo Usuário',
        'email' => 'novouser@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertSessionHasErrors(['terms']);
    $this->assertGuest();
});

test('registration fails when email is already registered', function () {
    User::factory()->create([
        'email' => 'existing@example.com',
    ]);

    $response = $this->post(route('attemptSignUp'), [
        'name' => 'Outro Usuário',
        'email' => 'existing@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'terms' => '1',
    ]);

    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
});
