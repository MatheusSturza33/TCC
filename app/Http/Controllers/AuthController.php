<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Exibe o formulário de login.
     */
    public function loginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Autentica o usuário pela matrícula e senha.
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'matricula' => ['required', 'string', 'max:50'],
            'senha' => ['required', 'string', 'max:255'],
        ], [
            'matricula.required' => 'Informe sua matrícula.',
            'matricula.max' => 'A matrícula não pode ter mais de 50 caracteres.',
            'senha.required' => 'Informe sua senha.',
            'senha.max' => 'A senha informada é inválida.',
        ]);

        $credentials = [
            'matricula' => $validated['matricula'],
            'password' => $validated['senha'],
            'situacao' => 'ativo',
        ];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['matricula' => 'Matrícula ou senha inválida.'])
                ->withInput($request->only('matricula', 'remember'));
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Login realizado com sucesso.');
    }

    /**
     * Encerra a sessão autenticada.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Você saiu do sistema com segurança.');
    }
}
