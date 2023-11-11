<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (!auth()->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'errors' => ['login' => ['The provided credentials do not match our records.']]
            ], 401);
        }

        // get user token
        $token = auth()->user()->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => auth()->user()]);
    }

    public function logout(): JsonResponse
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh(): JsonResponse
    {
        return response()->json(['token' => auth()->refresh()]);
    }

    public function user(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        // Create the user
        $user = User::create([
            'name' => $request->validated()['name'],
            'email' => $request->validated()['email'],
            'password' => Hash::make($request->validated()['password']),
        ]);

        // Generate a token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return a JSON response with the token
        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }
}
