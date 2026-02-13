<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserMockAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::query()->whereNull('deleted_at');

        // Search by name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by provider
        if ($request->has('provider') && $request->provider) {
            $query->where('provider', $request->provider);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Get users with attempt counts
        $users = $query->select('users.*')
            ->selectSub(function ($query) {
                $query->selectRaw('COUNT(*)')
                    ->from('user_mock_attempts')
                    ->whereColumn('user_mock_attempts.user_id', 'users.id');
            }, 'total_attempts')
            ->orderBy('users.created_at', 'desc')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the user details.
     */
    public function show($id)
    {
        $user = User::whereNull('deleted_at')->findOrFail($id);

        // Get mock activity summary
        $mockSummary = DB::table('user_mock_attempts')
            ->where('user_id', $id)
            ->select(
                DB::raw('COUNT(*) as total_attempts'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_mocks'),
                DB::raw('AVG(CASE WHEN status = "completed" THEN score END) as average_score'),
                DB::raw('MAX(CASE WHEN status = "completed" THEN score END) as best_score'),
                DB::raw('MAX(completed_at) as last_attempt')
            )
            ->first();

        // Get recent attempts (last 5)
        $recentAttempts = UserMockAttempt::where('user_id', $id)
            ->with(['exam', 'paper'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.users.show', compact('user', 'mockSummary', 'recentAttempts'));
    }

    /**
     * Block a user.
     */
    public function block($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'blocked';
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'User blocked successfully.');
    }

    /**
     * Unblock a user.
     */
    public function unblock($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'active';
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'User unblocked successfully.');
    }

    /**
     * Soft delete a user.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->deleted_at = now();
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
