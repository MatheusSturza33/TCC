<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'matricula' => 'required|string',
            'senha' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt([
            'matricula' => $credentials['matricula'],
            'password' => $credentials['senha'],
            'situacao' => 'ativo',
        ], $remember)) {

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withInput($request->only('matricula'))
            ->with('erro', 'Matrícula ou senha inválidos.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}