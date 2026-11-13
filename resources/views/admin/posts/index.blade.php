<x-layouts.app>
    <x-slot name="title">Discussions Management — Admin</x-slot>

    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Manage Discussions</h1>
        <p class="text-sm text-gray-500 mt-1">Pin important academic topics, close solved or inactive threads, remove violations</p>
    </x-slot>

    {{-- Filter bar --}}
    <x-card class="mb-6 !p-4">
        <form method="GET" action="{{ route('admin.posts.index') }}" class="flex flex-col sm:flex-row items-center gap-3">
            <input type="text" name="q" value="{{ $search }}" placeholder="Search discussion titles or content..."
                   class="w-full sm:flex-1 text-xs rounded-lg border-gray-300 p-2 shadow-sm focus:border-primary-800 focus:ring-primary-800" />
            
            <select name="category_id" class="text-xs rounded-lg border-gray-300 p-2 text-gray-700 shadow-sm">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            <select name="filter" class="text-xs rounded-lg border-gray-300 p-2 text-gray-700 shadow-sm">
                <option value="">All Statuses</option>
                <option value="pinned" {{ $filter === 'pinned' ? 'selected' : '' }}>Pinned</option>
                <option value="closed" {{ $filter === 'closed' ? 'selected' : '' }}>Closed</option>
            </select>

            <x-button type="submit" variant="primary" class="!py-2 !px-4 !text-xs whitespace-nowrap">Filter</x-button>
        </form>
    </x-card>

    {{-- Posts Table --}}
    <x-card class="overflow-x-auto !p-0">
        <table class="w-full text-left text-xs text-gray-600">
            <thead class="bg-gray-50 border-b border-gray-200 uppercase font-semibold text-gray-500">
                <tr>
                    <th class="py-3 px-4">Title & Category</th>
                    <th class="py-3 px-4">Author</th>
                    <th class="py-3 px-4">Replies & Views</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4 text-right">Moderation Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($posts as $p)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="py-3.5 px-4 max-w-xs">
                            <a href="{{ route('posts.show', $p) }}" class="font-bold text-gray-900 hover:text-primary-800 truncate block">
                                {{ $p->title }}
                            </a>
                            <span class="text-[11px] text-gray-400">{{ $p->category->name }} &middot; {{ $p->created_at->format('M d, Y') }}</span>
                        </td>

                        <td class="py-3.5 px-4">
                            <p class="font-medium text-gray-900">{{ $p->user->name }}</p>
                            <p class="text-[10px] text-gray-400">{{ $p->user->email }}</p>
                        </td>

                        <td class="py-3.5 px-4">
                            <span class="font-bold text-gray-800">{{ $p->replies_count }}</span> replies &middot;
                            <span class="text-gray-400">{{ $p->views }} views</span>
                        </td>

                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                @if($p->is_pinned)
                                    <span class="text-[10px] font-bold text-accent-700 bg-accent-50 px-1.5 py-0.5 rounded">📌 Pinned</span>
                                @endif
                                @if($p->is_closed)
                                    <span class="text-[10px] font-bold text-gray-700 bg-gray-100 px-1.5 py-0.5 rounded">🔒 Closed</span>
                                @endif
                                @if(! $p->is_pinned && ! $p->is_closed)
                                    <span class="text-[10px] text-gray-400">Normal</span>
                                @endif
                            </div>
                        </td>

                        <td class="py-3.5 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                {{-- Pin / Unpin --}}
                                <form method="POST" action="{{ route('admin.posts.pin', $p) }}">
                                    @csrf
                                    <button type="submit" class="font-semibold text-xs text-accent-600 hover:underline">
                                        {{ $p->is_pinned ? 'Unpin' : 'Pin' }}
                                    </button>
                                </form>

                                {{-- Lock / Unlock --}}
                                <form method="POST" action="{{ route('admin.posts.lock', $p) }}">
                                    @csrf
                                    <button type="submit" class="font-semibold text-xs text-gray-600 hover:underline ml-1">
                                        {{ $p->is_closed ? 'Reopen' : 'Close' }}
                                    </button>
                                </form>

                                {{-- Delete --}}
                                <form method="POST" action="{{ route('admin.posts.destroy', $p) }}" onsubmit="return confirm('Delete this post permanently?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-semibold text-xs text-red-600 hover:underline ml-1">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400">No discussions match your filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-card>

    <div class="mt-4">
        {{ $posts->links() }}
    </div>
</x-layouts.app>

