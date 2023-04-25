<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
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
        $response = Http::acceptJson()->post('http://127.0.0.1:8000/v1/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);
        if ($response->failed()) {
            return back()->withErrors(['email' => 'Credenciales incorrectas']);
        }
        $service = $response->json();
        $user = User::updateOrCreate(['email' => $request->email], $service['data']);
        if (!$user->accessToken) {
            $response = Http::acceptJson()->post('http://127.0.0.1:8000/oauth/token', [
                'grant_type' => 'password',
                'client_id' => '99029efc-1374-4e55-9070-535d099594c7',
                'client_secret' => 'j7HhxX6ZJtCcRDTkd3s2oNC2xWFslyVebPZPLTCh',
                'username' => $request->email,
                'password' => $request->password,
            ]);
            $access_token = $response->json();
            $user->accessToken()->create([
                'service_id' => $service['data']['id'],
                'access_token' => $access_token['access_token'],
                'refresh_token' => $access_token['refresh_token'],
                'expires_at' => now()->addSecond($access_token['expires_in']),
            ]);
        }
        Auth::login($user, $request->remember);
        return redirect()->intended(RouteServiceProvider::HOME);
        // return $user;
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
