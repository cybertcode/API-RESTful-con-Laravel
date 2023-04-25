<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);
        $response = Http::acceptJson()->post('http://127.0.0.1:8000/v1/register', $request->all());
        if ($response->status() == 422) {
            return back()->withErrors($response->json()['errors']);
        }
        $service = $response->json();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            // 'password' => Hash::make($request->password),
        ]);
        // Para el token
        $response = Http::acceptJson()->post('http://127.0.0.1:8000/oauth/token', [
            'grant_type' => 'password',
            'client_id' => config('services.cybertcode.client_id'),
            'client_secret' => config('services.cybertcode.client_secret'),
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

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
