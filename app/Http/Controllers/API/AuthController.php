<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|email|unique:users,email|max:255|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
        ]);

      User::query()->create($validator);

        return response()->json([
            'message' => 'User created successfully register'
        ], 201);
    }
    public function login(Request $request)
    {
        $validator = $request->validate([
            'email'=> 'required|email|exists:users,email',
            'password'=> 'required|string|min:6'
        ]);

        if (auth()->attempt($validator)) {
            $token = auth()->user()->createToken('authToken')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => auth()->user()
            ]);
        }

        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out'
        ]);
    }
}
