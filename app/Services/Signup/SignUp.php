<?php

declare(strict_types=1);

namespace App\Services\Signup;

use App\Enums\ThemeMode;
use App\Models\User;
use App\Services\Login\Login;
use Illuminate\Validation\ValidationException;

class SignUp
{
    private const BRAND_PRIMARY = '#182075';

    private const BRAND_SECONDARY = '#751919';

    public function __construct(protected Login $servicesLogin) {}

    public function execute(string $name, string $email, string $password): bool
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'theme' => ThemeMode::Dark,
            'brand_primary' => self::BRAND_PRIMARY,
            'brand_secondary' => self::BRAND_SECONDARY,
        ]);

        if (! $user) {
            throw ValidationException::withMessages(['error' => 'Houve um erro na tentativa de cadastro.']);
        }

        $success = $this->servicesLogin->execute(
            email: $user->email,
            password: $password,
            remember: false
        );

        if (! $success) {
            throw ValidationException::withMessages(['error' => 'Houve um erro na tentativa de login após o cadastro.']);
        }

        return true;
    }
}
