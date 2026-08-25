<?php

namespace App\Http\Controllers;

use App\Services\Login\Login as ServicesLogin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Login extends Controller
{
    public function __construct(protected ServicesLogin $servicesLogin) {}

    public function show(): View
    {
        $data = (object) [
            'title' => 'Acessar Conta',
        ];

        return view('login')->with('data', $data);
    }

    public function attemptLogin(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable'],
        ], [
            'email.required' => 'O e-mail é obrigatório',
            'email.email' => 'O e-mail deve ser válido',
            'password.required' => 'A senha é obrigatória',
        ]);

        $remember = $request->boolean('remember');

        $success = $this->servicesLogin->execute(
            email: $request->string('email')->value(),
            password: $request->string('password')->value(),
            remember: $remember
        );

        if (! $success) {
            return redirect()->back();
        }

        $request->session()->regenerate();

        return redirect()->route('home');
    }
}
