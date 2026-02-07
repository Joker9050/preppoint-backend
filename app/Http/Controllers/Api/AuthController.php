<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    /**
     * Email Signup
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'provider' => 'email',
            'google_id' => null,
        ]);

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'provider' => $user->provider,
                'token' => $token,
            ]
        ]);
    }

    /**
     * Email Login
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No account found. Please sign up first.'
            ], 404);
        }

        if ($user->provider === 'google' && is_null($user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'This account was created using Google. Please login using Google.'
            ], 403);
        }

        if ($user->provider === 'email' && !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.'
            ], 401);
        }

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'provider' => $user->provider,
                'token' => $token,
            ]
        ]);
    }

    /**
     * Google Auth (Login + Signup Handler)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function google(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'google_id' => 'required|string',
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'intent' => 'required|in:login,signup',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($request->intent === 'login') {
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'No account found. Please sign up using Google first.'
                ], 404);
            }

            if ($user->provider === 'email') {
                return response()->json([
                    'success' => false,
                    'message' => 'This email is registered with email & password. Please login using email.'
                ], 403);
            }

            // Assume google_id matches, as per logic
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Google login successful',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'provider' => $user->provider,
                    'token' => $token,
                ]
            ]);
        } elseif ($request->intent === 'signup') {
            if ($user) {
                return response()->json([
                    'success' => false,
                    'message' => 'This email is already registered. Please login instead.'
                ], 409);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => null,
                'google_id' => $request->google_id,
                'provider' => 'google',
            ]);

            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Google signup successful',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'provider' => $user->provider,
                    'token' => $token,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid intent'
        ], 400);
    }
}
