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
     */
    public function register(Request $request): JsonResponse
    {
        try {
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

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'provider' => 'email',
                'google_id' => null,
            ]);
            
            // Try to create token with detailed error handling
            $tokenError = null;
            $token = null;
            
            try {
                $token = $user->createToken('API Token')->plainTextToken;
            } catch (\Exception $tokenException) {
                $tokenError = [
                    'message' => $tokenException->getMessage(),
                    'code' => $tokenException->getCode(),
                    'line' => $tokenException->getLine(),
                    'file' => $tokenException->getFile()
                ];
                
                // Check if it's a database issue
                if (str_contains($tokenException->getMessage(), 'SQL') || 
                    str_contains($tokenException->getMessage(), 'table') ||
                    str_contains($tokenException->getMessage(), 'column')) {
                    $tokenError['hint'] = 'Database issue - check if personal_access_tokens table exists';
                }
            }
            
            // If token creation failed but user was created
            if ($tokenError) {
                return response()->json([
                    'success' => true,
                    'message' => 'User created successfully but token generation failed',
                    'data' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'provider' => $user->provider,
                        'token' => null,
                        'token_error' => $tokenError
                    ],
                    'warning' => 'Please login to get a token'
                ], 201);
            }
            
            // Success with token
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
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => [
                    'message' => $e->getMessage(),
                    'type' => get_class($e),
                    'code' => $e->getCode()
                ]
            ], 500);
        }
    }

    /**
     * Email Login
     */
    public function login(Request $request): JsonResponse
    {
        try {
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

            // Try to create token
            $tokenError = null;
            $token = null;
            
            try {
                $token = $user->createToken('API Token')->plainTextToken;
            } catch (\Exception $tokenException) {
                $tokenError = [
                    'message' => $tokenException->getMessage(),
                    'code' => $tokenException->getCode()
                ];
            }
            
            if ($tokenError) {
                return response()->json([
                    'success' => false,
                    'message' => 'Login successful but token creation failed',
                    'data' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'provider' => $user->provider,
                        'token' => null,
                    ],
                    'error' => $tokenError
                ], 200);
            }

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
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'error' => [
                    'message' => $e->getMessage(),
                    'type' => get_class($e)
                ]
            ], 500);
        }
    }

    /**
     * Google Auth (Login + Signup Handler)
     */
    public function google(Request $request): JsonResponse
    {
        try {
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

                // Try to create token
                $tokenError = null;
                $token = null;
                
                try {
                    $token = $user->createToken('API Token')->plainTextToken;
                } catch (\Exception $tokenException) {
                    $tokenError = [
                        'message' => $tokenException->getMessage(),
                        'code' => $tokenException->getCode()
                    ];
                }
                
                if ($tokenError) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Google login successful but token creation failed',
                        'data' => [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'provider' => $user->provider,
                            'token' => null,
                        ],
                        'error' => $tokenError
                    ], 200);
                }

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

                // Try to create token
                $tokenError = null;
                $token = null;
                
                try {
                    $token = $user->createToken('API Token')->plainTextToken;
                } catch (\Exception $tokenException) {
                    $tokenError = [
                        'message' => $tokenException->getMessage(),
                        'code' => $tokenException->getCode()
                    ];
                }
                
                if ($tokenError) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Google signup successful but token creation failed',
                        'data' => [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'provider' => $user->provider,
                            'token' => null,
                        ],
                        'error' => $tokenError
                    ], 201);
                }

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
                ], 201);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid intent'
            ], 400);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Google authentication failed',
                'error' => [
                    'message' => $e->getMessage(),
                    'type' => get_class($e)
                ]
            ], 500);
        }
    }
}