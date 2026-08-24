<?php

test('login page can be rendered', function () {
    $response = $this->get(route('login'));

    $response->assertOk();
    $response->assertViewIs('login');
});
