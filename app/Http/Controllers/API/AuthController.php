<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(StoreAuthRequest $request)
    {
        $validator = $request->validated();

        User::query()->create($validator);

        return response()->json([
            'message' => 'User created successfully register'
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|email|exists:users,email|max:255|string',
            'password' => 'required|string|min:6'
        ]);

        $email = $validator['email'];
        $password = $validator['password'];

        $user = User::query()->where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect'
            ], 401);
        }
        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'User logged out.'
        ]);
    }
}


