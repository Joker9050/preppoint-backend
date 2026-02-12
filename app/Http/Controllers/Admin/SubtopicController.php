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
    public function index()
    {
        $subtopics = Subtopic::with(['topic.subject', 'mcqs'])->orderBy('name')->paginate(15);
        return view('admin.subtopics.index', compact('subtopics'));
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

    public function show(Subtopic $subtopic)
    {
        $subtopic->load(['topic.subject']);
        return view('admin.subtopics.show', compact('subtopic'));
    }

    public function edit(Subtopic $subtopic)
    {
        return view('admin.subtopics.edit', compact('subtopic'));
    }

    public function update(Request $request, Subtopic $subtopic)
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
        $exists = Subtopic::where('topic_id', $subtopic->topic_id)
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

        return redirect()->route('admin.topics.show', $subtopic->topic)
            ->with('success', 'Subtopic updated successfully.');
    }

    public function destroy(Subtopic $subtopic)
    {
        // Check if subtopic has questions
        if ($subtopic->mcqs()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete subtopic with existing questions.');
        }

        $topic = $subtopic->topic;
        $subtopic->delete();
        return redirect()->route('admin.topics.show', $topic)
            ->with('success', 'Subtopic deleted successfully.');
    }
}
