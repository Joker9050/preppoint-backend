<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('subcategory')->get();
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $subcategories = Subcategory::all();
        return view('admin.subjects.create', compact('subcategories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subcategory_id' => 'required|exists:subcategories,id',
            'name' => 'required|string|max:255|unique:subjects',
            'priority' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Subject::create([
            'subcategory_id' => $request->subcategory_id,
            'name' => $request->name,
            'admin_id' => Auth::guard('admin')->user()->id,
            'priority' => $request->priority ?? 10,
        ]);

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    public function show(Subject $subject)
    {
        $subject->load('subcategory', 'topics');
        return view('admin.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        $subcategories = Subcategory::all();
        return view('admin.subjects.edit', compact('subject', 'subcategories'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validator = Validator::make($request->all(), [
            'subcategory_id' => 'required|exists:subcategories,id',
            'name' => 'required|string|max:255|unique:subjects,name,' . $subject->id,
            'priority' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $subject->update([
            'subcategory_id' => $request->subcategory_id,
            'name' => $request->name,
            'admin_id' => Auth::guard('admin')->id(),
            'priority' => $request->priority ?? 10,
        ]);

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }
}
