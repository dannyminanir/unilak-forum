<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements with department filters and pinned first.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $selectedDepartment = $request->query('department');

        if ($user?->role === 'student') {
            $selectedDepartment = $selectedDepartment ?? $user->department;

            if ($selectedDepartment && ! $user->canAccessDepartment($selectedDepartment)) {
                abort(403, 'You can only view announcements for your faculty.');
            }
        }

        $query = Announcement::with('admin')->published();

        if ($selectedDepartment) {
            $query->forDepartment($selectedDepartment);
        }

        $announcements = $query->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->paginate(10)
            ->withQueryString();

        $departments = $user?->role === 'student' && $user->department
            ? [$user->department]
            : [
                'Faculty of Computing & Information Sciences',
                'Faculty of Economic Sciences & Management',
                'Faculty of Law',
                'Faculty of Environmental Studies',
                'Faculty of Education',
            ];

        return view('announcements.index', [
            'announcements'      => $announcements,
            'departments'        => $departments,
            'selectedDepartment' => $selectedDepartment,
        ]);
    }

    /**
     * Show announcement creation form (admin only).
     */
    public function create(): View
    {
        Gate::authorize('create', Announcement::class);

        return view('announcements.create');
    }

    /**
     * Store new announcement (admin only).
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Announcement::class);

        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'content'      => ['required', 'string'],
            'department'   => ['nullable', 'string', 'max:150'],
            'is_pinned'    => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        Announcement::create([
            'admin_id'     => $request->user()->id,
            'title'        => $validated['title'],
            'content'      => $validated['content'],
            'department'   => $validated['department'] ?? null,
            'is_pinned'    => $request->boolean('is_pinned'),
            'published_at' => $validated['published_at'] ?? now(),
        ]);

        return redirect()->route('announcements.index')
            ->with('status', 'Announcement published successfully! 📢');
    }

    /**
     * Show announcement edit form (admin only).
     */
    public function edit(Announcement $announcement): View
    {
        Gate::authorize('update', $announcement);

        return view('announcements.edit', [
            'announcement' => $announcement,
        ]);
    }

    /**
     * Update an announcement (admin only).
     */
    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        Gate::authorize('update', $announcement);

        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'content'      => ['required', 'string'],
            'department'   => ['nullable', 'string', 'max:150'],
            'is_pinned'    => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        $announcement->update([
            'title'        => $validated['title'],
            'content'      => $validated['content'],
            'department'   => $validated['department'] ?? null,
            'is_pinned'    => $request->boolean('is_pinned'),
            'published_at' => $validated['published_at'] ?? $announcement->published_at,
        ]);

        return redirect()->route('announcements.index')
            ->with('status', 'Announcement updated successfully.');
    }

    /**
     * Delete announcement (admin only).
     */
    public function destroy(Announcement $announcement): RedirectResponse
    {
        Gate::authorize('delete', $announcement);

        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('status', 'Announcement removed.');
    }
}

