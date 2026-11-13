<?php

namespace App\Http\Controllers;

use App\Http\Requests\Forum\StoreResourceRequest;
use App\Models\Category;
use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ResourceController extends Controller
{
    /**
     * Display a listing of academic resources with search and category filters.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $selectedCategory = $request->query('category');
        $fileType = $request->query('type');
        $search = $request->query('q');

        $query = Resource::with(['user', 'category'])->latest();

        if ($user?->role === 'student' && $user->department) {
            $query->whereHas('category', function ($categoryQuery) use ($user) {
                $categoryQuery->where(function ($q) use ($user) {
                    $q->where('type', '!=', 'department')
                      ->orWhere('name', $user->department);
                });
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        if ($selectedCategory) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $selectedCategory));
        }

        if ($fileType) {
            $query->where('file_type', strtolower($fileType));
        }

        $resources = $query->paginate(12)->withQueryString();
        $categories = Category::ordered()->get();

        return view('resources.index', [
            'resources'        => $resources,
            'categories'       => $categories,
            'selectedCategory' => $selectedCategory,
            'fileType'         => $fileType,
            'search'           => $search,
        ]);
    }

    /**
     * Show upload form.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', Resource::class);

        $categories = Category::ordered()->get();
        $defaultCategoryId = $request->query('category_id');

        return view('resources.create', [
            'categories'        => $categories,
            'defaultCategoryId' => $defaultCategoryId,
        ]);
    }

    /**
     * Store a newly uploaded academic resource with renamed file hash.
     */
    public function store(StoreResourceRequest $request): RedirectResponse
    {
        Gate::authorize('create', Resource::class);

        $file = $request->file('file');
        $fileExtension = strtolower($file->getClientOriginalExtension());
        $fileSize = $file->getSize();

        // Safely rename file
        $hashedName = Str::random(32) . '.' . $fileExtension;
        $path = $file->storeAs('resources', $hashedName, 'public');

        $resource = Resource::create([
            'user_id'     => $request->user()->id,
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'description' => $request->description,
            'file_path'   => $path,
            'file_type'   => $fileExtension,
            'file_size'   => $fileSize,
            'downloads'   => 0,
        ]);

        return redirect()->route('resources.show', $resource)
            ->with('status', 'Academic resource uploaded successfully! 📚');
    }

    /**
     * Display the specified resource details.
     */
    public function show(Resource $resource): View
    {
        $resource->load(['user', 'category']);

        return view('resources.show', [
            'resource' => $resource,
        ]);
    }

    /**
     * Download the resource file and increment the download counter.
     */
    public function download(Resource $resource): BinaryFileResponse|RedirectResponse
    {
        Gate::authorize('download', $resource);

        if (! Storage::disk('public')->exists($resource->file_path)) {
            return back()->with('error', 'The requested file could not be found on the server.');
        }

        $resource->incrementDownloads();

        $fullPath = Storage::disk('public')->path($resource->file_path);
        $downloadName = Str::slug($resource->title) . '.' . $resource->file_type;

        return response()->download($fullPath, $downloadName);
    }

    /**
     * Delete resource (owner or admin only).
     */
    public function destroy(Resource $resource): RedirectResponse
    {
        Gate::authorize('delete', $resource);

        if (Storage::disk('public')->exists($resource->file_path)) {
            Storage::disk('public')->delete($resource->file_path);
        }

        $resource->delete();

        return redirect()->route('resources.index')
            ->with('status', 'Resource has been deleted.');
    }
}
