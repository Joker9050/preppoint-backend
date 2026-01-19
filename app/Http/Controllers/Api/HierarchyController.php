<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Subtopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HierarchyController extends Controller
{
    /**
     * Get complete hierarchy structure (subjects -> topics -> subtopics)
     * Used for navigation/sidebar in MCQ system
     */
    public function index()
    {
        try {
            $subjects = Subject::select([
                'subjects.id',
                'subjects.name',
                'subjects.priority',
                DB::raw('COUNT(DISTINCT topics.id) as topic_count'),
                DB::raw('COUNT(DISTINCT mcqs.id) as question_count')
            ])
            ->leftJoin('topics', 'subjects.id', '=', 'topics.subject_id')
            ->leftJoin('mcqs', 'topics.id', '=', 'mcqs.topic_id')
            ->groupBy('subjects.id', 'subjects.name', 'subjects.priority')
            ->orderBy('subjects.priority')
            ->orderBy('subjects.name')
            ->get();

            $hierarchy = [];

            foreach ($subjects as $subject) {
                $topics = Topic::select([
                    'topics.id',
                    'topics.name',
                    DB::raw('COUNT(DISTINCT subtopics.id) as subtopic_count'),
                    DB::raw('COUNT(DISTINCT mcqs.id) as question_count')
                ])
                ->leftJoin('subtopics', 'topics.id', '=', 'subtopics.topic_id')
                ->leftJoin('mcqs', 'topics.id', '=', 'mcqs.topic_id')
                ->where('topics.subject_id', $subject->id)
                ->groupBy('topics.id', 'topics.name')
                ->orderBy('topics.name')
                ->get();

                $subjectTopics = [];

                foreach ($topics as $topic) {
                    $subtopics = Subtopic::select([
                        'subtopics.id',
                        'subtopics.name',
                        DB::raw('COUNT(mcqs.id) as question_count')
                    ])
                    ->leftJoin('mcqs', function($join) {
                        $join->on('subtopics.id', '=', 'mcqs.subtopic_id')
                             ->on('mcqs.topic_id', '=', 'subtopics.topic_id');
                    })
                    ->where('subtopics.topic_id', $topic->id)
                    ->groupBy('subtopics.id', 'subtopics.name')
                    ->orderBy('subtopics.name')
                    ->get();

                    $subjectTopics[] = [
                        'id' => $topic->id,
                        'name' => $topic->name,
                        'subtopics' => $subtopics->map(function($subtopic) {
                            return [
                                'id' => $subtopic->id,
                                'name' => $subtopic->name,
                                'question_count' => (int) $subtopic->question_count
                            ];
                        }),
                        'question_count' => (int) $topic->question_count
                    ];
                }

                $hierarchy[] = [
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'topics' => $subjectTopics,
                    'question_count' => (int) $subject->question_count
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $hierarchy,
                'message' => 'Hierarchy retrieved successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Hierarchy API Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve hierarchy',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
