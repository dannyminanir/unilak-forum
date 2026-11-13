<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminCategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount(['posts', 'resources'])->ordered()->paginate(15);
        return view('admin.categories.index', ['categories' => $categories]);
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:categories,name'],
            'type'        => ['required', 'in:department,course,interest'],
            'description' => ['nullable', 'string', 'max:500'],
            'icon'        => ['nullable', 'string', 'max:10'],
            'color'       => ['required', 'string', 'regex:/^#[a-fA-F0-9]{6}$/'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
        ]);

        Category::create([
            'name'        => $validated['name'],
            'slug'        => Str::slug($validated['name']),
            'type'        => $validated['type'],
            'description' => $validated['description'] ?? null,
            'icon'        => $validated['icon'] ?? '📁',
            'color'       => $validated['color'],
            'sort_order'  => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.categories.index')->with('status', 'Category created successfully!');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', ['category' => $category]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($category->id)],
            'type'        => ['required', 'in:department,course,interest'],
            'description' => ['nullable', 'string', 'max:500'],
            'icon'        => ['nullable', 'string', 'max:10'],
            'color'       => ['required', 'string', 'regex:/^#[a-fA-F0-9]{6}$/'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
        ]);

        $category->update([
            'name'        => $validated['name'],
            'slug'        => Str::slug($validated['name']),
            'type'        => $validated['type'],
            'description' => $validated['description'] ?? null,
            'icon'        => $validated['icon'] ?? $category->icon,
            'color'       => $validated['color'],
            'sort_order'  => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.categories.index')->with('status', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('status', 'Category removed.');
    }
}

