<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * List all registered users with search and role filtering.
     */
    public function index(Request $request): View
    {
        $search = $request->query('q');
        $role = $request->query('role');
        $status = $request->query('status'); // banned / active

        $query = User::latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }

        if ($role) {
            $query->where('role', $role);
        }

        if ($status === 'banned') {
            $query->where('is_banned', true);
        } elseif ($status === 'active') {
            $query->where('is_banned', false);
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', [
            'users'  => $users,
            'search' => $search,
            'role'   => $role,
            'status' => $status,
        ]);
    }

    /**
     * Toggle ban/unban on a user.
     */
    public function toggleBan(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'You cannot ban your own administrative account.');
        }

        $user->update(['is_banned' => ! $user->is_banned]);
        $msg = $user->is_banned ? "User {$user->name} has been banned and suspended." : "User {$user->name} has been unbanned.";

        return back()->with('status', $msg);
    }

    /**
     * Change user role (student, moderator, admin).
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'in:student,moderator,admin'],
        ]);

        if ($user->id === $request->user()->id && $validated['role'] !== 'admin') {
            return back()->with('error', 'You cannot demote yourself from admin status.');
        }

        $user->update(['role' => $validated['role']]);

        return back()->with('status', "Role updated for {$user->name} to {$validated['role']}.");
    }

    /**
     * Delete user account.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        return back()->with('status', "User account {$user->name} has been deleted.");
    }
}

