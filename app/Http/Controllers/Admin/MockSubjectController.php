<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MockSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MockSubjectController extends Controller
{
    public function index()
    {
        $subjects = MockSubject::with(['topics.subtopics'])->orderBy('name')->paginate(15);
        return view('admin.mock-subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('admin.mock-subjects.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:mock_subjects,name',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        MockSubject::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.mock-subjects.index')
            ->with('success', 'Mock subject created successfully.');
    }

    public function show(MockSubject $mockSubject)
    {
        $mockSubject->load(['topics.subtopics']);
        return view('admin.mock-subjects.show', compact('mockSubject'));
    }

    public function edit(MockSubject $mockSubject)
    {
        return view('admin.mock-subjects.edit', compact('mockSubject'));
    }

    public function update(Request $request, MockSubject $mockSubject)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:mock_subjects,name,' . $mockSubject->id,
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $mockSubject->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.mock-subjects.index')
            ->with('success', 'Mock subject updated successfully.');
    }

    public function destroy(MockSubject $mockSubject)
    {
        // Check if subject has topics
        if ($mockSubject->topics()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete subject with existing topics.');
        }

        $mockSubject->delete();
        return redirect()->route('admin.mock-subjects.index')
            ->with('success', 'Mock subject deleted successfully.');
    }

    public function toggleStatus(MockSubject $mockSubject)
    {
        $newStatus = $mockSubject->status == 1 ? 0 : 1;
        $mockSubject->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', 'Subject status updated to ' . ($newStatus == 1 ? 'Active' : 'Inactive'));
    }
}
