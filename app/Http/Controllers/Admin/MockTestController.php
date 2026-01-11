<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MockTest;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Mcq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MockTestController extends Controller
{
    public function index()
    {
        $mockTests = MockTest::with('subject')->paginate(15);
        return view('admin.mock-tests.index', compact('mockTests'));
    }

    public function create()
    {
        $subjects = Subject::all();
        return view('admin.mock-tests.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'topic_ids' => 'required|array|min:1',
            'topic_ids.*' => 'exists:topics,id',
            'mcq_ids' => 'required|array|min:1',
            'mcq_ids.*' => 'exists:mcqs,id',
            'time_limit' => 'required|integer|min:1|max:300',
            'is_ssc_pyq' => 'nullable|boolean',
            'is_active' => 'required|boolean',
            'instructions' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validate that selected MCQs belong to selected topics
        $selectedTopics = $request->topic_ids;
        $selectedMcqs = $request->mcq_ids;

        $validMcqs = Mcq::whereIn('id', $selectedMcqs)
            ->whereIn('topic_id', $selectedTopics)
            ->pluck('id')
            ->toArray();

        if (count($validMcqs) !== count($selectedMcqs)) {
            return redirect()->back()
                ->withErrors(['mcq_ids' => 'Some selected MCQs do not belong to the selected topics.'])
                ->withInput();
        }

        MockTest::create([
            'title' => $request->title,
            'description' => $request->description,
            'subject_id' => $request->subject_id,
            'topic_ids' => $request->topic_ids,
            'mcq_ids' => $request->mcq_ids,
            'time_limit' => $request->time_limit,
            'total_questions' => count($request->mcq_ids),
            'is_ssc_pyq' => $request->is_ssc_pyq ?? false,
            'is_active' => $request->is_active,
            'instructions' => $request->instructions,
            'admin_id' => auth()->id(),
        ]);

        return redirect()->route('admin.mock-tests.index')
            ->with('success', 'Mock test created successfully.');
    }

    public function show(MockTest $mockTest)
    {
        $mockTest->load('subject');
        return view('admin.mock-tests.show', compact('mockTest'));
    }

    public function edit(MockTest $mockTest)
    {
        $subjects = Subject::all();
        return view('admin.mock-tests.edit', compact('mockTest', 'subjects'));
    }

    public function update(Request $request, MockTest $mockTest)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'topic_ids' => 'required|array|min:1',
            'topic_ids.*' => 'exists:topics,id',
            'mcq_ids' => 'required|array|min:1',
            'mcq_ids.*' => 'exists:mcqs,id',
            'time_limit' => 'required|integer|min:1|max:300',
            'is_ssc_pyq' => 'nullable|boolean',
            'is_active' => 'required|boolean',
            'instructions' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validate that selected MCQs belong to selected topics
        $selectedTopics = $request->topic_ids;
        $selectedMcqs = $request->mcq_ids;

        $validMcqs = Mcq::whereIn('id', $selectedMcqs)
            ->whereIn('topic_id', $selectedTopics)
            ->pluck('id')
            ->toArray();

        if (count($validMcqs) !== count($selectedMcqs)) {
            return redirect()->back()
                ->withErrors(['mcq_ids' => 'Some selected MCQs do not belong to the selected topics.'])
                ->withInput();
        }

        $mockTest->update([
            'title' => $request->title,
            'description' => $request->description,
            'subject_id' => $request->subject_id,
            'topic_ids' => $request->topic_ids,
            'mcq_ids' => $request->mcq_ids,
            'time_limit' => $request->time_limit,
            'total_questions' => count($request->mcq_ids),
            'is_ssc_pyq' => $request->is_ssc_pyq ?? false,
            'is_active' => $request->is_active,
            'instructions' => $request->instructions,
        ]);

        return redirect()->route('admin.mock-tests.index')
            ->with('success', 'Mock test updated successfully.');
    }

    public function destroy(MockTest $mockTest)
    {
        $mockTest->delete();
        return redirect()->route('admin.mock-tests.index')
            ->with('success', 'Mock test deleted successfully.');
    }

    public function toggleStatus(MockTest $mockTest)
    {
        $mockTest->update(['is_active' => !$mockTest->is_active]);

        return redirect()->back()
            ->with('success', 'Mock test status updated successfully.');
    }

    public function getTopics(Request $request, Subject $subject)
    {
        $topics = $subject->topics;
        return response()->json($topics);
    }

    public function getMcqs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:subjects,id',
            'topic_ids' => 'required|array|min:1',
            'topic_ids.*' => 'exists:topics,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $mcqs = Mcq::where('subject_id', $request->subject_id)
            ->whereIn('topic_id', $request->topic_ids)
            ->where('status', 'active')
            ->with('topic')
            ->get()
            ->map(function ($mcq) {
                return [
                    'id' => $mcq->id,
                    'question' => $mcq->question,
                    'topic_name' => $mcq->topic->name,
                    'difficulty' => $mcq->difficulty,
                ];
            });

        return response()->json($mcqs);
    }
}
