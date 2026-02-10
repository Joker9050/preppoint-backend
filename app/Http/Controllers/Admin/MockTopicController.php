<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MockSubject;
use App\Models\MockTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MockTopicController extends Controller
{
    public function index()
    {
        $topics = MockTopic::with(['subject', 'subtopics'])->orderBy('name')->paginate(15);
        return view('admin.mock-topics.index', compact('topics'));
    }

    public function create()
    {
        $subjects = MockSubject::where('status', 'active')->get();
        return view('admin.mock-topics.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:mock_subjects,id',
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:mock_topics,slug',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $slug = $request->slug ?: \Str::slug($request->name);

        MockTopic::create([
            'subject_id' => $request->subject_id,
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.mock-topics.index')
            ->with('success', 'Mock topic created successfully.');
    }

    public function show(MockTopic $mockTopic)
    {
        $mockTopic->load(['subject', 'subtopics']);
        return view('admin.mock-topics.show', compact('mockTopic'));
    }

    public function edit(MockTopic $mockTopic)
    {
        $subjects = MockSubject::where('status', 'active')->get();
        return view('admin.mock-topics.edit', compact('mockTopic', 'subjects'));
    }

    public function update(Request $request, MockTopic $mockTopic)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:mock_subjects,id',
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:mock_topics,slug,' . $mockTopic->id,
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $slug = $request->slug ?: \Str::slug($request->name);

        $mockTopic->update([
            'subject_id' => $request->subject_id,
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.mock-topics.index')
            ->with('success', 'Mock topic updated successfully.');
    }

    public function destroy(MockTopic $mockTopic)
    {
        // Check if topic has subtopics
        if ($mockTopic->subtopics()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete topic with existing subtopics.');
        }

        $mockTopic->delete();
        return redirect()->route('admin.mock-topics.index')
            ->with('success', 'Mock topic deleted successfully.');
    }

    public function toggleStatus(MockTopic $mockTopic)
    {
        $newStatus = $mockTopic->status == 1 ? 0 : 1;
        $mockTopic->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', 'Topic status updated to ' . ($newStatus == 1 ? 'Active' : 'Inactive'));
    }
}
