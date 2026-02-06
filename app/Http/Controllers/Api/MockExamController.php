<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MockExam;
use App\Models\MockExamPaper;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MockExamController extends Controller
{
    /**
     * GET /api/exams
     * Fetch all available exams (SSC CGL, SSC MTS)
     */
    public function index(): JsonResponse
    {
        try {
            $exams = MockExam::where('status', true)
                ->select('id', 'name', 'short_name', 'category','mode','ui_template')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $exams
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch exams'
            ], 500);
        }
    }

    /**
     * GET /api/exams/{exam_slug}/papers
     * Fetch all mock papers & PYQs for an exam
     */
    public function getPapers(string $examSlug): JsonResponse
    {
        try {
            // Find exam by slug (assuming we add slug to mock_exams table)
            $exam = MockExam::where('status', true)->first();

            if (!$exam) {
                return response()->json([
                    'success' => false,
                    'message' => 'Exam not found'
                ], 404);
            }

            $papers = MockExamPaper::where('exam_id', $exam->id)
                ->where('is_live', true)
                ->select(
                    'id',
                    'title',
                    'paper_type',
                    'year',
                    'shift',
                    'total_questions',
                    'duration_minutes',
                    'difficulty_level'
                )
                ->orderBy('year', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $papers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch papers'
            ], 500);
        }
    }

    /**
     * GET /api/popular-papers
     * Fetch popular papers for homepage display
     */
    public function getPopularPapers(): JsonResponse
    {
        try {
            // First, let's check if there are any papers at all
            $totalPapers = MockExamPaper::count();

            if ($totalPapers === 0) {
                // Return empty array if no papers exist
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'No papers available'
                ]);
            }

            $papers = MockExamPaper::with('exam:id,name,category,mode')
                ->select(
                    'id',
                    'exam_id',
                    'title',
                    'paper_type',
                    'year',
                    'shift',
                    'total_questions',
                    'duration_minutes',
                    'difficulty_level'
                )
                ->orderBy('year', 'desc')
                ->orderBy('created_at', 'desc')
                ->limit(20) // Limit for homepage
                ->get()
                ->map(function ($paper) {
                    return [
                        'id' => $paper->id,
                        'name' => $paper->title ?: 'Untitled Paper',
                        'exam_name' => $paper->exam->name ?? 'Unknown Exam',
                        'category' => $paper->exam->category ?? 'Unknown',
                        'mode' => $paper->exam->mode ?? 'Online',
                        'paper_type' => $paper->paper_type ?: 'General',
                        'year' => $paper->year ?: date('Y'),
                        'shift' => $paper->shift ?: 'General',
                        'total_questions' => $paper->total_questions ?: 0,
                        'duration_minutes' => $paper->duration_minutes ?: 60,
                        'difficulty_level' => $paper->difficulty_level ?: 'Medium',
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $papers,
                'total' => $totalPapers
            ]);
        } catch (\Exception $e) {
            \Log::error('Popular papers API error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch popular papers: ' . $e->getMessage()
            ], 500);
        }
    }
}
