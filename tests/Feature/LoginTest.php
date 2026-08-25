<?php

use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

test('login page can be rendered', function () {
    $response = $this->get(route('login'));

    $response->assertOk();
    $response->assertViewIs('login');
    $response->assertSee('Acessar Conta');
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post(route('attemptLogin'), [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('home'));
    $this->assertAuthenticatedAs($user);
});

test('users can authenticate with remember me enabled', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post(route('attemptLogin'), [
        'email' => $user->email,
        'password' => 'password123',
        'remember' => 'on',
    ]);

    $response->assertRedirect(route('home'));
    $this->assertAuthenticatedAs($user);
});

test('users cannot authenticate with invalid password', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post(route('attemptLogin'), [
        'email' => $user->email,
        'password' => 'wrongpassword10',
    ]);

    $response->assertSessionHasErrors('error');
    $this->assertGuest();
});

test('validation fails when fields are invalid or missing', function () {
    $response = $this->post(route('attemptLogin'), [
        'email' => 'invalid-email',
        'password' => '',
    ]);

    $response->assertSessionHasErrors(['email', 'password']);
    $this->assertGuest();
});

test('users are rate limited after 5 failed attempts', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);

    $throttleKey = Str::transliterate(Str::lower($user->email).'|127.0.0.1');
    RateLimiter::clear($throttleKey);

    for ($i = 0; $i < 5; $i++) {
        $this->post(route('attemptLogin'), [
            'email' => $user->email,
            'password' => 'wrongpassword10',
        ]);
    }

    $response = $this->post(route('attemptLogin'), [
        'email' => $user->email,
        'password' => 'wrongpassword10',
    ]);

    $response->assertSessionHasErrors('error');
    $this->assertGuest();
});
