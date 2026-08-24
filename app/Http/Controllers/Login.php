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
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:10', 'string'],
            'remember' => ['nullable'],
        ], [
            'email.required' => 'O e-mail é obrigatório',
            'email.email' => 'O e-mail deve ser válido',
            'password.required' => 'A senha é obrigatória',
            'password.min' => 'A senha deve ter pelo menos 10 caracteres',
        ]);

        $remember = $request->boolean('remember');

        $success = $this->servicesLogin->execute(
            email: $credentials['email'],
            password: $credentials['password'],
            remember: $remember
        );

        if (! $success) {
            return redirect()->back();
        }

        $request->session()->regenerate();

        return redirect()->route('home');
    }
}
