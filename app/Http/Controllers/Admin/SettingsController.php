<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($group)
    {
        $settings = Setting::where('group', $group)->get();
        return view('admin.settings.edit', compact('settings', 'group'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $group)
    {
        $settings = Setting::where('group', $group)->get();

        foreach ($settings as $setting) {
            $key = $setting->key;
            if ($request->has($key)) {
                $setting->update(['value' => $request->input($key)]);
            }
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
