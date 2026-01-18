<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobUpdate;
use Illuminate\Http\Request;

class JobUpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobUpdates = JobUpdate::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.job-updates.index', compact('jobUpdates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.job-updates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        JobUpdate::create($request->all());

        return redirect()->route('admin.job-updates.index')
            ->with('success', 'Job update created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JobUpdate $jobUpdate)
    {
        return view('admin.job-updates.show', compact('jobUpdate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobUpdate $jobUpdate)
    {
        return view('admin.job-updates.edit', compact('jobUpdate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobUpdate $jobUpdate)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $jobUpdate->update($request->all());

        return redirect()->route('admin.job-updates.index')
            ->with('success', 'Job update updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobUpdate $jobUpdate)
    {
        $jobUpdate->delete();

        return redirect()->route('admin.job-updates.index')
            ->with('success', 'Job update deleted successfully.');
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(JobUpdate $jobUpdate)
    {
        $jobUpdate->update([
            'status' => $jobUpdate->status === 'active' ? 'inactive' : 'active'
        ]);

        return redirect()->route('admin.job-updates.index')
            ->with('success', 'Job update status updated successfully.');
    }
}
