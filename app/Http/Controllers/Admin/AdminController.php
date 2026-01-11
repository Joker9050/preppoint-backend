<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        $admin = auth('admin')->user();
        return view('admin.dashboard', compact('admin'));
    }

    /**
     * Display admin profile.
     */
    public function profile()
    {
        return view('admin.profile');
    }
}
