<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MockQuestion;
use App\Models\MockQuestionOption;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MockQuestionController extends Controller
{
    /**
     * POST /api/questions/{question_id}/check
     * Check selected option and return correctness + explanation
     */
    public function checkAnswer(Request $request, int $questionId): JsonResponse
    {
        try {
            $request->validate([
                'selected_option' => 'required|string|in:A,B,C,D'
            ]);

            $question = MockQuestion::with('options')->find($questionId);

            if (!$question) {
                return response()->json([
                    'success' => false,
                    'message' => 'Question not found'
                ], 404);
            }

            $selectedOption = $question->options->where('option_label', $request->selected_option)->first();
            $correctOption = $question->options->where('is_correct', true)->first();

            if (!$selectedOption || !$correctOption) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid question data'
                ], 500);
            }

            $isCorrect = $selectedOption->is_correct;

            return response()->json([
                'success' => true,
                'data' => [
                    'is_correct' => $isCorrect,
                    'selected_option' => $request->selected_option,
                    'correct_option' => $correctOption->option_label,
                    'explanation' => $question->explanation,
                    'correct_option_text' => $correctOption->option_text
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check answer'
            ], 500);
        }
    }
}
