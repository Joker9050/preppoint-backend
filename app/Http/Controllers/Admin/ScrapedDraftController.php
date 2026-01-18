<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScrapedDraft;
use Illuminate\Http\Request;

class ScrapedDraftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $scrapedDrafts = ScrapedDraft::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.scraped-drafts.index', compact('scrapedDrafts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.scraped-drafts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'source_url' => 'nullable|url',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        ScrapedDraft::create($request->all());

        return redirect()->route('admin.scraped-drafts.index')
            ->with('success', 'Scraped draft created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ScrapedDraft $scrapedDraft)
    {
        return view('admin.scraped-drafts.show', compact('scrapedDraft'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ScrapedDraft $scrapedDraft)
    {
        return view('admin.scraped-drafts.edit', compact('scrapedDraft'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ScrapedDraft $scrapedDraft)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'source_url' => 'nullable|url',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $scrapedDraft->update($request->all());

        return redirect()->route('admin.scraped-drafts.index')
            ->with('success', 'Scraped draft updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ScrapedDraft $scrapedDraft)
    {
        $scrapedDraft->delete();

        return redirect()->route('admin.scraped-drafts.index')
            ->with('success', 'Scraped draft deleted successfully.');
    }
}
