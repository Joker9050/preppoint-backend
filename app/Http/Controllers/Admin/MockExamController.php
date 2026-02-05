<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MockExam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MockExamController extends Controller
{
    public function index(Request $request)
    {
        $query = MockExam::query();

        // Apply filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $exams = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.mock-exams.index', compact('exams'));
    }

    public function create()
    {
        return view('admin.mock-exams.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:mock_exams,name',
            'short_name' => 'required|string|max:50|unique:mock_exams,short_name',
            'category' => 'required|string|max:50',
            'mode' => 'required|in:online,offline',
            'status' => 'required|boolean',
            'ui_template' => 'nullable|in:eduquity,tcs',
            'description' => 'nullable|string',
            'slug' => 'nullable|string|max:120|unique:mock_exams,slug',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        MockExam::create($request->all());

        return redirect()->route('admin.mock-exams.index')
            ->with('success', 'Mock exam created successfully.');
    }

    public function show(MockExam $mockExam)
    {
        $mockExam->load(['papers' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);
        return view('admin.mock-exams.show', compact('mockExam'));
    }

    public function edit(MockExam $mockExam)
    {
        return view('admin.mock-exams.edit', compact('mockExam'));
    }

    public function update(Request $request, MockExam $mockExam)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:mock_exams,name,' . $mockExam->id,
            'short_name' => 'required|string|max:50|unique:mock_exams,short_name,' . $mockExam->id,
            'category' => 'required|string|max:50',
            'mode' => 'required|in:online,offline',
            'status' => 'required|boolean',
            'ui_template' => 'nullable|in:eduquity,tcs',
            'description' => 'nullable|string',
            'slug' => 'nullable|string|max:120|unique:mock_exams,slug,' . $mockExam->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $mockExam->update($request->all());

        return redirect()->route('admin.mock-exams.index')
            ->with('success', 'Mock exam updated successfully.');
    }

    public function destroy(MockExam $mockExam)
    {
        // Check if exam has papers
        if ($mockExam->papers()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete exam with existing papers.');
        }

        $mockExam->delete();
        return redirect()->route('admin.mock-exams.index')
            ->with('success', 'Mock exam deleted successfully.');
    }

    public function toggleStatus(MockExam $mockExam)
    {
        $mockExam->update(['status' => !$mockExam->status]);

        return redirect()->back()
            ->with('success', 'Exam status updated successfully.');
    }
}
