<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MockTopic;
use App\Models\MockSubtopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MockSubtopicController extends Controller
{
    public function index()
    {
        $subtopics = MockSubtopic::with(['topic.subject'])->orderBy('name')->paginate(15);
        return view('admin.mock-subtopics.index', compact('subtopics'));
    }

    public function create()
    {
        $topics = MockTopic::where('status', 'active')->with('subject')->get();
        return view('admin.mock-subtopics.create', compact('topics'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'topic_id' => 'required|exists:mock_topics,id',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        MockSubtopic::create([
            'topic_id' => $request->topic_id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.mock-subtopics.index')
            ->with('success', 'Mock subtopic created successfully.');
    }

    public function show(MockSubtopic $mockSubtopic)
    {
        $mockSubtopic->load(['topic.subject']);
        return view('admin.mock-subtopics.show', compact('mockSubtopic'));
    }

    public function edit(MockSubtopic $mockSubtopic)
    {
        $topics = MockTopic::where('status', 'active')->with('subject')->get();
        return view('admin.mock-subtopics.edit', compact('mockSubtopic', 'topics'));
    }

    public function update(Request $request, MockSubtopic $mockSubtopic)
    {
        $validator = Validator::make($request->all(), [
            'topic_id' => 'required|exists:mock_topics,id',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $mockSubtopic->update([
            'topic_id' => $request->topic_id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.mock-subtopics.index')
            ->with('success', 'Mock subtopic updated successfully.');
    }

    public function destroy(MockSubtopic $mockSubtopic)
    {
        // Check if subtopic has questions
        if ($mockSubtopic->mcqs()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete subtopic with existing questions.');
        }

        $mockSubtopic->delete();
        return redirect()->route('admin.mock-subtopics.index')
            ->with('success', 'Mock subtopic deleted successfully.');
    }
}
