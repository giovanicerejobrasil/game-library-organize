<?php

namespace App\Services\Login;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Login
{
    private const REMEMBER_DURATION_MINUTES = 4320; // 72 horas

    public function execute(string $email, string $password, bool $remember): bool
    {
        $throttleKey = $this->throttleKey($email);

        $this->ensureIsNotRateLimited($throttleKey);

        $credentials = [
            'email' => $email,
            'password' => $password,
        ];

        if (! Auth::guard('web')->attempt($credentials, $remember)) {
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages(['error' => 'Email ou senha inválidos.']);
        }

        RateLimiter::clear($throttleKey);

        if ($remember) {
            $this->adjustRememberCookieDuration();
        }

        return true;
    }

    private function adjustRememberCookieDuration(): void
    {
        $recallerName = Auth::getRecallerName();

        $queuedCookie = Cookie::queued($recallerName);

        if ($queuedCookie) {
            Cookie::queue(
                Cookie::make(
                    name: $recallerName,
                    value: $queuedCookie->getValue(),
                    minutes: self::REMEMBER_DURATION_MINUTES,
                    path: $queuedCookie->getPath(),
                    domain: $queuedCookie->getDomain(),
                    secure: $queuedCookie->isSecure(),
                    httpOnly: $queuedCookie->isHttpOnly(),
                    sameSite: $queuedCookie->getSameSite()
                )
            );
        }
    }

    protected function ensureIsNotRateLimited(string $throttleKey): void
    {
        if (! RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($throttleKey);

        throw ValidationException::withMessages([
            'error' => "Muitas tentativas. Tente novamente em {$seconds} segundos.",
        ]);
    }

    protected function throttleKey(string $email): string
    {
        return Str::transliterate(Str::lower($email).'|'.request()->ip());
    }
}
