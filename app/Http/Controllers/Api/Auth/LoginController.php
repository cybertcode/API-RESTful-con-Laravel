<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    //access login method
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);
        // $user = User::where('email', $validated['email'])->first();
        $user = User::where('email', $validated['email'])->firstOrFail();
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => '¡Los datos ingresados son incorrectos!'], 404);
        }
        return UserResource::make($user);
    }

}
