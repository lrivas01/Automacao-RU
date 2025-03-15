<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller {
    /**
     * Exibe a tela de login.
     */
    public function create(): Response {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Processa o login.
     */
    public function store(LoginRequest $request): RedirectResponse {
        $request->authenticate(); // Autentica o usuário
        $request->session()->regenerate(); // Protege a sessão

        Log::info('Usuário autenticado', [
            'email' => $request->email,
            'guard_students' => Auth::guard('students')->check(),
            'guard_web' => Auth::guard('web')->check(),
        ]);

        // Verifica se o usuário é um estudante
        if (Auth::guard('students')->user()) {
            return redirect()->route('student.dashboard')->with('status', 'Login bem-sucedido!');
        }

        // Caso contrário, assume que é um administrador
        if (Auth::guard('web')->user()) {
            return redirect()->route('admin.dashboard')->with('status', 'Login bem-sucedido!');
        }

        return redirect()->route('login')->withErrors([
            'email' => 'Erro ao autenticar. Verifique suas credenciais.',
        ]);
    }

    /**
     * Realiza o logout.
     */
    public function destroy(Request $request): RedirectResponse {
        if (Auth::guard('students')->check()) {
            Auth::guard('students')->logout();
        } elseif (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Logout realizado com sucesso!');
    }
}
