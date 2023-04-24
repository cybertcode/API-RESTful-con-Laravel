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
        $request->validate(
            [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]
        );
        $user = User::where('email', $request->email)->firstOrFail();
        if (Hash::check($request->password, $user->password)) {
            return UserResource::make($user);
        } else {
            return response()->json(['message' => '¡Los datos ingresos no son correctos!', 404]);
        }
    }
}
