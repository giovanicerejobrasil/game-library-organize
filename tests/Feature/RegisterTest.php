<?php

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
