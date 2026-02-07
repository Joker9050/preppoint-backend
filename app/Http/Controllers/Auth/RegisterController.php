<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        // Case 1: Email already exists
        if ($user) {
            throw ValidationException::withMessages([
                'email' => ['This email is already registered. Please login instead.'],
            ]);
        }

        // Case 2: New email - create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'provider' => 'email',
            'email_verified' => 1, // Email accounts are verified by default
        ]);

        Auth::login($user);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Handle Google OAuth registration
     */
    public function registerWithGoogle(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'google_id' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        // Case A: Email already exists
        if ($user) {
            throw ValidationException::withMessages([
                'email' => ['This email is already registered. Please login instead.'],
            ]);
        }

        // Case B: New email - create Google user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'google_id' => $request->google_id,
            'provider' => 'google',
            'password' => null,
            'email_verified' => 1, // Google accounts are pre-verified
        ]);

        Auth::login($user);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Google registration successful',
            'user' => $user,
            'token' => $token,
        ], 201);
    }
}
