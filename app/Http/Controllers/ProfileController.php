<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the authenticated student's profile or another student's profile with tabs.
     */
    public function show(Request $request, ?User $user = null): View
    {
        $targetUser = $user && $user->exists ? $user : $request->user();
        $tab = $request->query('tab', 'posts');

        $myPosts = $targetUser->posts()
            ->visibleTo($request->user())
            ->with(['category', 'tags'])
            ->withCount('replies')
            ->latest()
            ->paginate(10, ['*'], 'posts_page');

        $myResources = $targetUser->resources()
            ->with('category')
            ->latest()
            ->paginate(10, ['*'], 'resources_page');

        $savedPosts = collect();
        if ($targetUser->id === $request->user()->id) {
            $savedPosts = $targetUser->savedPosts()
                ->visibleTo($request->user())
                ->with(['category', 'user'])
                ->withCount('replies')
                ->latest('saved_posts.created_at')
                ->paginate(10, ['*'], 'saved_page');
        }

        return view('profile.show', [
            'user' => $targetUser,
            'isOwn' => $targetUser->id === $request->user()->id,
            'tab' => $tab,
            'myPosts' => $myPosts,
            'myResources' => $myResources,
            'savedPosts' => $savedPosts,
        ]);
    }

    /**
     * Update user details and profile photo.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:150'],
            'year_of_study' => ['nullable', 'integer', 'between:1,6'],
            'bio' => ['nullable', 'string', 'max:500'],
            'photo' => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
        ]);

        if ($request->hasFile('photo')) {
            // Remove previous custom photo if exists
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $path = $request->file('photo')->store('avatars', 'public');
            $user->profile_photo = $path;
        }

        $user->name = $validated['name'];
        $user->department = $validated['department'] ?? null;
        $user->year_of_study = $validated['year_of_study'] ?? null;
        $user->bio = $validated['bio'] ?? null;
        $user->save();

        return back()->with('status', 'Profile updated successfully! ✨');
    }
}
