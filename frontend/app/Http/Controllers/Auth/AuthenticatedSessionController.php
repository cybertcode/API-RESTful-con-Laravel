<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    // public function store(LoginRequest $request): RedirectResponse //Anterior
    public function store(Request $request)
    {
        //Anterior
        // $request->authenticate();
        // $request->session()->regenerate();
        // return redirect()->intended(RouteServiceProvider::HOME);
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->post('http://127.0.0.1:8000/v1/login', [
            'email' => $request->email,
            'password' => $request->password]);
        // return $response->json();
        $response = $response->json();
        $user = User::updateOrcreate(['email' => $request->email], $response['data']);
        return $user;
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
