<?php

use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\SavedPostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

// ── Root Landing / Welcome ───────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('welcome');

// ── Guest-only routes ────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

// ── Authenticated routes ─────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // ── In-App Notifications ─────────────────────────────────────────────────
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // ── Universal Campus Search ──────────────────────────────────────────────
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');

    // ── Student Profiles ─────────────────────────────────────────────────────
    Route::get('/profile/{user?}', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // ── Categories ───────────────────────────────────────────────────────────
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');

    // ── Posts (Forum Discussions) ───────────────────────────────────────────
    Route::get('/discussions', [PostController::class, 'index'])->name('posts.index');
    Route::get('/discussions/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/discussions', [PostController::class, 'store'])->name('posts.store');
    Route::get('/discussions/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/discussions/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/discussions/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/discussions/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/discussions/{post}/pin', [PostController::class, 'togglePin'])->name('posts.pin');
    Route::post('/discussions/{post}/lock', [PostController::class, 'toggleLock'])->name('posts.lock');

    // ── Replies ──────────────────────────────────────────────────────────────
    Route::post('/discussions/{post}/replies', [ReplyController::class, 'store'])->name('replies.store');
    Route::put('/replies/{reply}', [ReplyController::class, 'update'])->name('replies.update');
    Route::delete('/replies/{reply}', [ReplyController::class, 'destroy'])->name('replies.destroy');
    Route::post('/replies/{reply}/best-answer', [ReplyController::class, 'markBestAnswer'])->name('replies.best-answer');

    // ── Polymorphic Voting ──────────────────────────────────────────────────
    Route::post('/discussions/{post}/vote', [VoteController::class, 'votePost'])->name('posts.vote');
    Route::post('/replies/{reply}/vote', [VoteController::class, 'voteReply'])->name('replies.vote');

    // ── Flagging / Reporting System ──────────────────────────────────────────
    Route::post('/discussions/{post}/report', [ReportController::class, 'reportPost'])->name('posts.report');
    Route::post('/replies/{reply}/report', [ReportController::class, 'reportReply'])->name('replies.report');

    // ── Saved Posts (Bookmarks) ──────────────────────────────────────────────
    Route::get('/saved-discussions', [SavedPostController::class, 'index'])->name('posts.saved');
    Route::post('/discussions/{post}/save', [SavedPostController::class, 'toggle'])->name('posts.save');

    // ── Tags ─────────────────────────────────────────────────────────────────
    Route::get('/tags/{tag:slug}', [TagController::class, 'show'])->name('tags.show');

    // ── Academic Resources ───────────────────────────────────────────────────
    Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
    Route::get('/resources/upload', [ResourceController::class, 'create'])->name('resources.create');
    Route::post('/resources', [ResourceController::class, 'store'])->name('resources.store');
    Route::get('/resources/{resource}', [ResourceController::class, 'show'])->name('resources.show');
    Route::get('/resources/{resource}/download', [ResourceController::class, 'download'])->name('resources.download');
    Route::delete('/resources/{resource}', [ResourceController::class, 'destroy'])->name('resources.destroy');

    // ── Campus Announcements ─────────────────────────────────────────────────
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

    // ── Full Admin Suite (Admin Middleware Guarded) ──────────────────────────
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        // Overview Dashboard
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Manage Users
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/ban', [AdminUserController::class, 'toggleBan'])->name('users.ban');
        Route::post('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.role');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // Manage Discussions
        Route::get('/posts', [AdminPostController::class, 'index'])->name('posts.index');
        Route::post('/posts/{post}/pin', [AdminPostController::class, 'togglePin'])->name('posts.pin');
        Route::post('/posts/{post}/lock', [AdminPostController::class, 'toggleLock'])->name('posts.lock');
        Route::delete('/posts/{post}', [AdminPostController::class, 'destroy'])->name('posts.destroy');

        // Manage Categories
        Route::resource('categories', AdminCategoryController::class)->except(['show']);

        // Manage Reports
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports');
        Route::post('/reports/{report}/dismiss', [AdminReportController::class, 'dismiss'])->name('reports.dismiss');
        Route::post('/reports/{report}/delete', [AdminReportController::class, 'deleteItem'])->name('reports.delete');
    });
});
