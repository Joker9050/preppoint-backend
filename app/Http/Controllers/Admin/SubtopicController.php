<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\Subtopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubtopicController extends Controller
{
    public function index(Request $request)
    {
        $query = Subtopic::with(['topic.subject', 'mcqs']);

        // Apply filters
        if ($request->filled('subject_id')) {
            $query->whereHas('topic.subject', function($q) use ($request) {
                $q->where('id', $request->subject_id);
            });
        }

        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        $subtopics = $query->orderBy('name')->paginate(15);

        // Get filter options
        $subjects = \App\Models\Subject::orderBy('name')->get();
        $topics = \App\Models\Topic::with('subject')->orderBy('name')->get();

        // Get total counts for stats
        $totalTopics = \App\Models\Topic::count();
        $totalSubjects = \App\Models\Subject::count();

        return view('admin.subtopics.index', compact('subtopics', 'subjects', 'topics', 'totalTopics', 'totalSubjects'));
    }

    public function create(Topic $topic)
    {
        return view('admin.subtopics.create', compact('topic'));
    }

    public function store(Request $request, Topic $topic)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check for unique name within topic
        $exists = Subtopic::where('topic_id', $topic->id)
            ->where('name', $request->name)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['name' => 'Subtopic name must be unique within the topic.'])
                ->withInput();
        }

        Subtopic::create([
            'topic_id' => $topic->id,
            'name' => $request->name,
            'admin_id' => Auth::guard('admin')->user()->id,
        ]);

        return redirect()->route('admin.topics.show', $topic)
            ->with('success', 'Subtopic created successfully.');
    }

    public function show(Topic $topic, Subtopic $subtopic)
    {
        $subtopic->load(['topic.subject', 'mcqs']);
        return view('admin.subtopics.show', compact('subtopic'));
    }

    public function edit(Topic $topic, Subtopic $subtopic)
    {
        return view('admin.subtopics.edit', compact('topic', 'subtopic'));
    }

    public function update(Request $request, Topic $topic, Subtopic $subtopic)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check for unique name within topic (excluding current)
        $exists = Subtopic::where('topic_id', $topic->id)
            ->where('name', $request->name)
            ->where('id', '!=', $subtopic->id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['name' => 'Subtopic name must be unique within the topic.'])
                ->withInput();
        }

        $subtopic->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.topics.show', $topic)
            ->with('success', 'Subtopic updated successfully.');
    }

    public function destroy(Request $request, $topicId = null, Subtopic $subtopic = null)
    {
        // Handle both nested and non-nested routes
        $topic = null;
        if ($topicId && $subtopic) {
            // Nested route: /topics/{topic}/subtopics/{subtopic}
            $topic = Topic::findOrFail($topicId);
        } elseif ($subtopic) {
            // Non-nested route: /subtopics/{subtopic}
            $topic = $subtopic->topic;
        } else {
            // Fallback for non-nested route where subtopic is passed as parameter
            $subtopic = Subtopic::findOrFail($topicId); // $topicId is actually subtopic ID
            $topic = $subtopic->topic;
        }

        // Check if subtopic has questions
        if ($subtopic->mcqs()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete subtopic with existing questions.');
        }

        $subtopic->delete();

        // Redirect based on the current route
        $routeName = $request->route()->getName();
        if ($routeName === 'admin.subtopics.destroy') {
            // From subtopics index or show page, redirect to subtopics index
            return redirect()->route('admin.subtopics.index')
                ->with('success', 'Subtopic deleted successfully.');
        } elseif ($routeName === 'admin.topics.subtopics.destroy') {
            // From nested route, redirect to topic show page
            return redirect()->route('admin.topics.show', $topic)
                ->with('success', 'Subtopic deleted successfully.');
        } else {
            // Default fallback
            return redirect()->route('admin.subtopics.index')
                ->with('success', 'Subtopic deleted successfully.');
        }
    }
}
