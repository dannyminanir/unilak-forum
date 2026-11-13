<x-layouts.app>
    <x-slot name="title">{{ $category->name }}</x-slot>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-3xl p-2 rounded-xl" style="background-color: {{ $category->color }}15;">
                    {{ $category->icon ?? '📁' }}
                </span>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs uppercase font-bold tracking-wider px-2 py-0.5 rounded-full"
                              style="background-color: {{ $category->color }}20; color: {{ $category->color }};">
                            {{ $category->type }}
                        </span>
                        <h1 class="text-xl font-bold text-gray-900">{{ $category->name }}</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $category->description }}</p>
                </div>
            </div>

            <a href="{{ route('posts.create', ['category_id' => $category->id]) }}">
                <x-button variant="secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Topic
                </x-button>
            </a>
        </div>
    </x-slot>

    {{-- Filter / Sort Tabs --}}
    <div class="flex items-center justify-between border-b border-gray-200 pb-3 mb-6">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('categories.show', [$category, 'sort' => 'latest']) }}"
               class="px-3 py-1.5 rounded-lg font-medium transition {{ $currentSort === 'latest' ? 'bg-primary-800 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Latest
            </a>
            <a href="{{ route('categories.show', [$category, 'sort' => 'discussed']) }}"
               class="px-3 py-1.5 rounded-lg font-medium transition {{ $currentSort === 'discussed' ? 'bg-primary-800 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Most Discussed
            </a>
            <a href="{{ route('categories.show', [$category, 'sort' => 'unanswered']) }}"
               class="px-3 py-1.5 rounded-lg font-medium transition {{ $currentSort === 'unanswered' ? 'bg-primary-800 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Unanswered
            </a>
        </div>
        <span class="text-xs text-gray-400 font-medium">
            {{ $posts->total() }} discussions
        </span>
    </div>

    {{-- Topics List --}}
    <div class="space-y-3">
        @forelse($posts as $post)
            <x-card class="hover:border-primary-200 transition duration-150 border border-transparent">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            @if($post->is_pinned)
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-accent-600 bg-accent-50 px-2 py-0.5 rounded-full">
                                    📌 Pinned
                                </span>
                            @endif
                            @if($post->is_closed)
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                                    🔒 Closed
                                </span>
                            @endif
                            <span class="text-xs text-gray-400">
                                Posted by <strong class="text-gray-700">{{ $post->user->name }}</strong> &middot; {{ $post->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <a href="{{ route('posts.show', $post) }}" class="block">
                            <h2 class="text-base font-semibold text-gray-900 hover:text-primary-800 transition line-clamp-1">
                                {{ $post->title }}
                            </h2>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2 leading-relaxed">
                                {{ Str::limit(strip_tags($post->content), 160) }}
                            </p>
                        </a>

                        @if($post->tags->isNotEmpty())
                            <div class="flex items-center gap-1.5 mt-3 flex-wrap">
                                @foreach($post->tags as $t)
                                    <x-tag size="sm">{{ $t->name }}</x-tag>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Right Stats --}}
                    <div class="flex flex-col items-end justify-between self-stretch text-right flex-shrink-0">
                        <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-full {{ $post->replies_count > 0 ? 'bg-primary-50 text-primary-800' : 'bg-gray-100 text-gray-500' }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            {{ $post->replies_count }}
                        </span>
                        <span class="text-xs text-gray-400 mt-2">
                            {{ $post->views }} views
                        </span>
                    </div>
                </div>
            </x-card>
        @empty
            <x-card class="text-center py-12">
                <div class="text-3xl mb-2">💬</div>
                <h3 class="font-semibold text-gray-900">No discussions here yet</h3>
                <p class="text-xs text-gray-500 mt-1">Be the first student to ask a question or share knowledge in this category!</p>
                <div class="mt-4">
                    <a href="{{ route('posts.create', ['category_id' => $category->id]) }}">
                        <x-button variant="secondary">Start Discussion</x-button>
                    </a>
                </div>
            </x-card>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $posts->links() }}
    </div>
</x-layouts.app>

