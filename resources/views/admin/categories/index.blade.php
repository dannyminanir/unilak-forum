<x-layouts.app>
    <x-slot name="title">Manage Categories — Admin</x-slot>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manage Categories</h1>
                <p class="text-sm text-gray-500 mt-1">Faculties, course boards, and student interest categories</p>
            </div>
            <a href="{{ route('admin.categories.create') }}">
                <x-button variant="secondary" class="!text-xs">
                    + Add Category
                </x-button>
            </a>
        </div>
    </x-slot>

    <x-card class="overflow-x-auto !p-0">
        <table class="w-full text-left text-xs text-gray-600">
            <thead class="bg-gray-50 border-b border-gray-200 uppercase font-semibold text-gray-500">
                <tr>
                    <th class="py-3 px-4">Icon & Name</th>
                    <th class="py-3 px-4">Type</th>
                    <th class="py-3 px-4">Discussions & Materials</th>
                    <th class="py-3 px-4">Color</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($categories as $c)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-2.5">
                                <span class="text-xl p-1 rounded" style="background-color: {{ $c->color }}15;">
                                    {{ $c->icon ?? '📁' }}
                                </span>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $c->name }}</p>
                                    <p class="text-[10px] text-gray-400 font-mono">{{ $c->slug }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="py-3.5 px-4">
                            <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-gray-100 text-gray-700">
                                {{ $c->type }}
                            </span>
                        </td>

                        <td class="py-3.5 px-4">
                            <span class="font-semibold text-gray-900">{{ $c->posts_count }}</span> posts &middot;
                            <span class="text-gray-400">{{ $c->resources_count }} files</span>
                        </td>

                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-2">
                                <span class="w-3.5 h-3.5 rounded-full border border-black/10" style="background-color: {{ $c->color }};"></span>
                                <span class="font-mono text-[11px] text-gray-500">{{ $c->color }}</span>
                            </div>
                        </td>

                        <td class="py-3.5 px-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.categories.edit', $c) }}" class="font-semibold text-primary-800 hover:underline">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $c) }}" onsubmit="return confirm('Delete this category and its associated content?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-semibold text-red-600 hover:underline">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-card>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</x-layouts.app>

