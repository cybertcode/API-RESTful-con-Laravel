<?php
namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Http;

trait Token
{
    public function getAccessToken($user, $service)
    {
        $response = Http::acceptJson()->post('http://127.0.0.1:8000/oauth/token', [
            'grant_type' => 'password',
            'client_id' => config('services.cybertcode.client_id'),
            'client_secret' => config('services.cybertcode.client_secret'),
            'username' => request('email'),
            'password' => request('password'),
        ]);
        if ($response->failed()) {
            throw new Exception('Error obteniendo el token de acceso');
        }
        $access_token = $response->json();
        // dd($access_token);
        $user->accessToken()->create([
            'service_id' => $service['data']['id'],
            'access_token' => $access_token['access_token'],
            'refresh_token' => $access_token['refresh_token'],
            'expires_at' => now()->addSecond($access_token['expires_in']),
        ]);
    }
}
