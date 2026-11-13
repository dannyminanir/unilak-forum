<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Reply;
use App\Models\Report;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminReportController extends Controller
{
    /**
     * Display the moderation dashboard with platform metrics and pending reports.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'pending');

        $reports = Report::with(['user', 'reportable'])
            ->where('status', $status)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $metrics = [
            'total_users'       => User::count(),
            'total_posts'       => Post::count(),
            'total_replies'     => Reply::count(),
            'total_resources'   => Resource::count(),
            'pending_reports'   => Report::where('status', 'pending')->count(),
            'resolved_reports'  => Report::where('status', 'resolved')->count(),
        ];

        return view('admin.reports', [
            'reports' => $reports,
            'metrics' => $metrics,
            'status'  => $status,
        ]);
    }

    /**
     * Dismiss a report without deleting content.
     */
    public function dismiss(Report $report): RedirectResponse
    {
        $report->update([
            'status'      => 'resolved',
            'admin_notes' => 'Dismissed: No violation found.',
        ]);

        return back()->with('status', 'Report marked as resolved.');
    }

    /**
     * Delete the reported item and mark report as resolved.
     */
    public function deleteItem(Report $report): RedirectResponse
    {
        if ($report->reportable) {
            $report->reportable->delete();
        }

        $report->update([
            'status'      => 'resolved',
            'admin_notes' => 'Content deleted by moderation.',
        ]);

        return back()->with('status', 'Reported content was successfully removed and resolved.');
    }
}

