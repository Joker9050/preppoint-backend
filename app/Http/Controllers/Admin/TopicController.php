<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TopicController extends Controller
{
    public function index()
    {
        $topics = Topic::with('subject', 'subtopics')->get();
        return view('admin.topics.index', compact('topics'));
    }

    public function create()
    {
        $subjects = Subject::all();
        return view('admin.topics.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check for unique name within subject
        $exists = Topic::where('subject_id', $request->subject_id)
            ->where('name', $request->name)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['name' => 'Topic name must be unique within the subject.'])
                ->withInput();
        }

        Topic::create([
            'subject_id' => $request->subject_id,
            'name' => $request->name,
            'admin_id' => auth()->id(),
        ]);

        return redirect()->route('admin.topics.index')
            ->with('success', 'Topic created successfully.');
    }

    public function show(Topic $topic)
    {
        $topic->load('subject', 'subtopics');
        return view('admin.topics.show', compact('topic'));
    }

    public function edit(Topic $topic)
    {
        $subjects = Subject::all();
        return view('admin.topics.edit', compact('topic', 'subjects'));
    }

    public function update(Request $request, Topic $topic)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check for unique name within subject (excluding current)
        $exists = Topic::where('subject_id', $request->subject_id)
            ->where('name', $request->name)
            ->where('id', '!=', $topic->id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['name' => 'Topic name must be unique within the subject.'])
                ->withInput();
        }

        $topic->update([
            'subject_id' => $request->subject_id,
            'name' => $request->name,
        ]);

        return redirect()->route('admin.topics.index')
            ->with('success', 'Topic updated successfully.');
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();
        return redirect()->route('admin.topics.index')
            ->with('success', 'Topic deleted successfully.');
    }
}
