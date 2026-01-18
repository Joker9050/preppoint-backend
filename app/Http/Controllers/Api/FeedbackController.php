<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FeedbackController extends Controller
{
    /**
     * Store a new feedback submission
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'name' => 'nullable|string|max:100',
                'email' => 'nullable|email|max:255',
                'rating' => 'required|integer|min:1|max:5',
                'feedback' => 'required|string|min:10|max:2000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Create the feedback record
            $feedback = Feedback::create([
                'name' => trim($request->input('name', '')),
                'email' => trim($request->input('email', '')),
                'rating' => $request->input('rating'),
                'feedback' => trim($request->input('feedback')),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your feedback! We appreciate your input.',
                'data' => [
                    'id' => $feedback->id,
                    'created_at' => $feedback->created_at->toISOString()
                ]
            ], 201);

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Feedback submission error: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your feedback. Please try again later.'
            ], 500);
        }
    }
}
