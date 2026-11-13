<x-layouts.app>
    <x-slot name="title">Discussions</x-slot>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Student Discussions</h1>
                <p class="text-sm text-gray-500 mt-1">Ask questions, share insights, and discuss academic topics with peers</p>
            </div>
            <a href="{{ route('posts.create') }}">
                <x-button variant="secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Discussion
                </x-button>
            </a>
        </div>
    </x-slot>

    {{-- Search & Filter Controls --}}
    <div class="space-y-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 border-b border-gray-200 pb-4">
            {{-- Search Bar --}}
            <form method="GET" action="{{ route('posts.index') }}" class="relative w-full md:w-80">
                <input type="hidden" name="category" value="{{ $selectedCategory }}">
                <input type="hidden" name="sort" value="{{ $currentSort }}">
                <input type="text"
                       name="q"
                       value="{{ $search }}"
                       placeholder="Search topics, questions, tags..."
                       class="w-full text-xs rounded-lg border-gray-300 pl-9 pr-3 py-2 shadow-sm focus:border-primary-800 focus:ring-primary-800" />
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </form>

            {{-- Filter Tabs & Category Dropdown --}}
            <div class="flex items-center gap-2 flex-wrap">
                <div class="flex items-center gap-1 text-xs">
                    <a href="{{ route('posts.index', array_filter(['category' => $selectedCategory, 'q' => $search, 'sort' => 'latest'])) }}"
                       class="px-2.5 py-1.5 rounded-lg font-medium transition {{ $currentSort === 'latest' ? 'bg-primary-800 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        Latest
                    </a>
                    <a href="{{ route('posts.index', array_filter(['category' => $selectedCategory, 'q' => $search, 'sort' => 'votes'])) }}"
                       class="px-2.5 py-1.5 rounded-lg font-medium transition {{ $currentSort === 'votes' ? 'bg-primary-800 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        Top Voted
                    </a>
                    <a href="{{ route('posts.index', array_filter(['category' => $selectedCategory, 'q' => $search, 'sort' => 'discussed'])) }}"
                       class="px-2.5 py-1.5 rounded-lg font-medium transition {{ $currentSort === 'discussed' ? 'bg-primary-800 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        Most Discussed
                    </a>
                    <a href="{{ route('posts.index', array_filter(['category' => $selectedCategory, 'q' => $search, 'sort' => 'unanswered'])) }}"
                       class="px-2.5 py-1.5 rounded-lg font-medium transition {{ $currentSort === 'unanswered' ? 'bg-primary-800 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        Unanswered
                    </a>
                </div>

                {{-- Category Filter --}}
                <form method="GET" action="{{ route('posts.index') }}">
                    <input type="hidden" name="sort" value="{{ $currentSort }}">
                    <input type="hidden" name="q" value="{{ $search }}">
                    <select name="category" onchange="this.form.submit()"
                            class="text-xs rounded-lg border-gray-300 bg-white py-1.5 pl-3 pr-8 text-gray-700 shadow-sm focus:border-primary-800 focus:ring-primary-800">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ $selectedCategory === $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>

    {{-- Discussions Feed --}}
    <div class="space-y-3">
        @forelse($posts as $post)
            <x-card class="hover:border-primary-200 transition duration-150 border border-transparent">
                <div class="flex items-start gap-4">
                    {{-- Upvotes display column --}}
                    <div class="flex flex-col items-center justify-center w-10 text-center flex-shrink-0 pt-0.5">
                        <span class="text-sm font-bold {{ $post->votes_count > 0 ? 'text-accent-600' : ($post->votes_count < 0 ? 'text-red-500' : 'text-gray-500') }}">
                            {{ $post->votes_count ?? 0 }}
                        </span>
                        <span class="text-[10px] text-gray-400 uppercase tracking-tight">votes</span>
                    </div>

                    {{-- Post details --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                            <a href="{{ route('categories.show', $post->category) }}">
                                <x-tag :color="$post->category->color" size="sm">
                                    {{ $post->category->icon }} {{ $post->category->name }}
                                </x-tag>
                            </a>

                            @if($post->is_pinned)
                                <span class="text-xs font-bold text-accent-600 bg-accent-50 px-2 py-0.5 rounded-full">📌 Pinned</span>
                            @endif
                            @if($post->is_closed)
                                <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">🔒 Closed</span>
                            @endif

                            <span class="text-xs text-gray-400">
                                by <strong class="text-gray-700">{{ $post->user->name }}</strong> &middot; {{ $post->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <a href="{{ route('posts.show', $post) }}" class="block">
                            <h2 class="text-base font-semibold text-gray-900 hover:text-primary-800 transition line-clamp-1">
                                {{ $post->title }}
                            </h2>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2 leading-relaxed">
                                {{ Str::limit(strip_tags($post->content), 170) }}
                            </p>
                        </a>

                        @if($post->tags->isNotEmpty())
                            <div class="flex items-center gap-1.5 mt-3 flex-wrap">
                                @foreach($post->tags as $t)
                                    <x-tag :href="route('tags.show', $t)" size="sm">#{{ $t->name }}</x-tag>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Right Metrics --}}
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
                <div class="text-3xl mb-2">🎓</div>
                <h3 class="font-semibold text-gray-900">No discussions found</h3>
                <p class="text-xs text-gray-500 mt-1">Be the first to start a conversation in the UNILAK Student Forum!</p>
                <div class="mt-4">
                    <a href="{{ route('posts.create') }}">
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
