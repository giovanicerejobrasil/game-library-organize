<?php

use Livewire\Livewire;

test('password strength component can be rendered', function () {
    Livewire::test('password-strength')
        ->assertOk()
        ->assertSee('Senha')
        ->assertSee('Muito Fraco')
        ->assertSee('Muito Forte');
});

test('password strength calculates score correctly for different passwords', function () {
    $component = Livewire::test('password-strength');

    // Empty
    $component->set('password', '')
        ->assertSet('score', 0);

    // Only lowercase + short (score 1)
    $component->set('password', 'pass')
        ->assertSet('score', 1);

    // Lowercase + uppercase + short (score 2)
    $component->set('password', 'Pass')
        ->assertSet('score', 2);

    // Lowercase + uppercase + number + short (score 3)
    $component->set('password', 'Pass1')
        ->assertSet('score', 3);

    // Lowercase + uppercase + number + special + short (score 4)
    $component->set('password', 'Pass1!')
        ->assertSet('score', 4);

    // Lowercase + uppercase + number + special + length >= 8 (score 5)
    $component->set('password', 'Password123!')
        ->assertSet('score', 5);
});
