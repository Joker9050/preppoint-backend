<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mcq;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Subtopic;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class McqController extends Controller
{
    /**
     * Get MCQs by subject with topics and subtopics
     */
    public function getBySubject(Subject $subject): JsonResponse
    {
        try {
            $mcqs = $subject->topics()
                ->with(['mcqs' => function($query) {
                    $query->where('status', 'active')
                          ->orderBy('question_no');
                }, 'mcqs.subtopic'])
                ->get()
                ->pluck('mcqs')
                ->flatten()
                ->map(function($mcq) {
                    return [
                        'id' => $mcq->id,
                        'topic_id' => $mcq->topic_id,
                        'subtopic_id' => $mcq->subtopic_id,
                        'question_no' => $mcq->question_no,
                        'question' => $mcq->question,
                        'question_code' => $mcq->question_code,
                        'question_image' => $mcq->question_image,
                        'options' => $mcq->options,
                        'correct_option' => $mcq->correct_option,
                        'explanation' => $mcq->explanation,
                        'difficulty' => $mcq->difficulty,
                        'status' => $mcq->status,
                        'topic' => $mcq->topic ? [
                            'id' => $mcq->topic->id,
                            'name' => $mcq->topic->name,
                            'subject_id' => $mcq->topic->subject_id
                        ] : null,
                        'subtopic' => $mcq->subtopic ? [
                            'id' => $mcq->subtopic->id,
                            'name' => $mcq->subtopic->name,
                            'topic_id' => $mcq->subtopic->topic_id
                        ] : null
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $mcqs,
                'total' => $mcqs->count(),
                'subject' => [
                    'id' => $subject->id,
                    'name' => $subject->name
                ],
                'message' => 'MCQs retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve MCQs: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get MCQs by topic
     */
    public function getByTopic(Topic $topic): JsonResponse
    {
        try {
            $mcqs = $topic->mcqs()
                ->with('subtopic')
                ->where('status', 'active')
                ->orderBy('question_no')
                ->get()
                ->map(function($mcq) use ($topic) {
                    return [
                        'id' => $mcq->id,
                        'topic_id' => $mcq->topic_id,
                        'subtopic_id' => $mcq->subtopic_id,
                        'question_no' => $mcq->question_no,
                        'question' => $mcq->question,
                        'question_code' => $mcq->question_code,
                        'question_image' => $mcq->question_image,
                        'options' => $mcq->options,
                        'correct_option' => $mcq->correct_option,
                        'explanation' => $mcq->explanation,
                        'difficulty' => $mcq->difficulty,
                        'status' => $mcq->status,
                        'topic' => [
                            'id' => $topic->id,
                            'name' => $topic->name,
                            'subject_id' => $topic->subject_id
                        ],
                        'subtopic' => $mcq->subtopic ? [
                            'id' => $mcq->subtopic->id,
                            'name' => $mcq->subtopic->name,
                            'topic_id' => $mcq->subtopic->topic_id
                        ] : null
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $mcqs,
                'total' => $mcqs->count(),
                'topic' => [
                    'id' => $topic->id,
                    'name' => $topic->name,
                    'subject_id' => $topic->subject_id
                ],
                'message' => 'MCQs retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve MCQs: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get MCQs by subtopic
     */
    public function getBySubtopic(Subtopic $subtopic): JsonResponse
    {
        try {
            $mcqs = $subtopic->mcqs()
                ->with('topic')
                ->where('status', 'active')
                ->orderBy('question_no')
                ->get()
                ->map(function($mcq) use ($subtopic) {
                    return [
                        'id' => $mcq->id,
                        'topic_id' => $mcq->topic_id,
                        'subtopic_id' => $mcq->subtopic_id,
                        'question_no' => $mcq->question_no,
                        'question' => $mcq->question,
                        'question_code' => $mcq->question_code,
                        'question_image' => $mcq->question_image,
                        'options' => $mcq->options,
                        'correct_option' => $mcq->correct_option,
                        'explanation' => $mcq->explanation,
                        'difficulty' => $mcq->difficulty,
                        'status' => $mcq->status,
                        'topic' => $mcq->topic ? [
                            'id' => $mcq->topic->id,
                            'name' => $mcq->topic->name,
                            'subject_id' => $mcq->topic->subject_id
                        ] : null,
                        'subtopic' => [
                            'id' => $subtopic->id,
                            'name' => $subtopic->name,
                            'topic_id' => $subtopic->topic_id
                        ]
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $mcqs,
                'total' => $mcqs->count(),
                'subtopic' => [
                    'id' => $subtopic->id,
                    'name' => $subtopic->name,
                    'topic_id' => $subtopic->topic_id
                ],
                'message' => 'MCQs retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve MCQs: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get MCQs with optimized filtering and pagination
     * Direct queries only - no relationship loading
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Start with base query - select only required columns
            $query = Mcq::select([
                'id',
                'topic_id',
                'subtopic_id',
                'question_no',
                'question',
                'question_code',
                'question_image',
                'options',
                'difficulty',
                'status'
            ])->where('status', 'active');

            // Apply filters directly on indexed columns
            if ($request->has('subject_id')) {
                // Join with topics table to filter by subject_id
                $query->join('topics', 'mcqs.topic_id', '=', 'topics.id')
                      ->where('topics.subject_id', $request->subject_id)
                      ->select('mcqs.*'); // Re-select mcqs columns after join
            }

            if ($request->has('topic_id')) {
                $query->where('topic_id', $request->topic_id);
            }

            if ($request->has('subtopic_id')) {
                $query->where('subtopic_id', $request->subtopic_id);
            }

            if ($request->has('difficulty')) {
                $query->where('difficulty', $request->difficulty);
            }

            // Always paginate for performance
            $perPage = $request->get('per_page', 20);
            $page = $request->get('page', 1);

            $mcqs = $query->orderBy('question_no')
                         ->paginate($perPage, ['*'], 'page', $page);

            // Load topic and subtopic relationships if needed
            $loadRelationships = $request->has('subject_id') || $request->has('topic_id') || $request->has('subtopic_id');

            if ($loadRelationships) {
                $mcqs->getCollection()->load(['topic', 'subtopic']);
            }

            // Conditionally include sensitive fields
            $withAnswers = $request->boolean('with_answers', false);
            $formattedMcqs = $mcqs->getCollection()->map(function($mcq) use ($withAnswers, $loadRelationships) {
                $baseData = [
                    'id' => $mcq->id,
                    'topic_id' => $mcq->topic_id,
                    'subtopic_id' => $mcq->subtopic_id,
                    'question_no' => $mcq->question_no,
                    'question' => $mcq->question,
                    'question_code' => $mcq->question_code,
                    'question_image' => $mcq->question_image,
                    'options' => $mcq->options,
                    'difficulty' => $mcq->difficulty
                ];

                // Include topic and subtopic data when relationships are loaded
                if ($loadRelationships) {
                    $baseData['topic'] = $mcq->topic ? [
                        'id' => $mcq->topic->id,
                        'name' => $mcq->topic->name,
                        'subject_id' => $mcq->topic->subject_id
                    ] : null;

                    $baseData['subtopic'] = $mcq->subtopic ? [
                        'id' => $mcq->subtopic->id,
                        'name' => $mcq->subtopic->name,
                        'topic_id' => $mcq->subtopic->topic_id
                    ] : null;
                }

                // Only include answers when explicitly requested
                if ($withAnswers) {
                    $baseData['correct_option'] = $mcq->correct_option;
                    $baseData['explanation'] = $mcq->explanation;
                }

                return $baseData;
            });

            return response()->json([
                'success' => true,
                'data' => $formattedMcqs,
                'pagination' => [
                    'total' => $mcqs->total(),
                    'per_page' => $mcqs->perPage(),
                    'current_page' => $mcqs->currentPage(),
                    'last_page' => $mcqs->lastPage(),
                    'from' => $mcqs->firstItem(),
                    'to' => $mcqs->lastItem()
                ],
                'message' => 'MCQs retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve MCQs: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}