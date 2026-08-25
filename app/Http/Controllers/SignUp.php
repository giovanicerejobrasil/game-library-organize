<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Signup\SignUp as ServicesSignUp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SignUp extends Controller
{
    public function __construct(protected ServicesSignUp $servicesSignUp) {}

    public function show(): View
    {
        $data = (object) [
            'title' => 'Criar Conta',
        ];

        return view('register')->with('data', $data);
    }

    public function attemptSignUp(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted'],
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'name.max' => 'O campo nome não pode exceder 255 caracteres.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O campo e-mail deve ser um e-mail válido.',
            'email.unique' => 'Houve um erro na tentativa de cadastro deste e-mail.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'O campo senha deve ter no mínimo 8 caracteres.',
            'password.confirmed' => 'As senhas não coincidem.',
            'terms.accepted' => 'Por favor, leia e aceite os termos para prosseguir.',
        ]);

        $terms = $request->boolean('terms');

        $success = $this->servicesSignUp->execute(
            name: $request->string('name')->value(),
            email: $request->string('email')->value(),
            password: $request->string('password')->value()
        );

        if (! $success) {
            return redirect()->back();
        }

        $request->session()->regenerate();

        return redirect()->route('home');
    }
}
