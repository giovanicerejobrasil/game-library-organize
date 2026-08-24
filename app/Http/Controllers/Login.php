<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Login extends Controller
{
    public function show()
    {
        $data = (object) [
            'title' => 'Acessar Conta',
        ];

        return view('login')->with('data', $data);
    }

    public function attemptLogin(Request $request)
    {
        dd($request->request);
    }
}
