<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class PostController extends Controller
{
    public function store()
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . auth()->user()->accessToken->access_token,
        ])->post('http://127.0.0.1:8000/v1/posts', [
            'name' => 'título prueba',
            'slug' => 'titulo-de-prueba',
            'extract' => 'extracto de contenido',
            'body' => 'Contenido principal del post',
            'category_id' => 1,
        ]);
        return $response->json();
    }
}
