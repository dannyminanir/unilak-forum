<x-layouts.app>
    <x-slot name="title">Search</x-slot>

    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Campus Search</h1>
        <p class="text-sm text-gray-500 mt-1">Find questions, past papers, notes, and students across UNILAK</p>
    </x-slot>

    {{-- Main Search Bar --}}
    <div class="mb-8 max-w-2xl">
        <form method="GET" action="{{ route('search.index') }}" class="relative">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <input
                type="text"
                name="q"
                value="{{ $query }}"
                autofocus
                placeholder="Search topics, questions, lecture notes, students..."
                class="w-full rounded-xl border border-gray-300 py-3 pl-11 pr-24 text-sm shadow-sm focus:border-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-800"
            />
            <svg class="w-5 h-5 text-gray-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <div class="absolute right-2 top-2">
                <x-button type="submit" variant="primary" class="!py-1.5 !px-3 !text-xs">Search</x-button>
            </div>
        </form>
    </div>

    @if(strlen($query) >= 2)
        {{-- Tabs --}}
        <div class="flex items-center gap-2 border-b border-gray-200 pb-3 mb-6 text-xs">
            <a href="{{ route('search.index', ['q' => $query, 'tab' => 'all']) }}"
               class="px-3 py-1.5 rounded-lg font-semibold transition {{ $tab === 'all' ? 'bg-primary-800 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                All Results
            </a>
            <a href="{{ route('search.index', ['q' => $query, 'tab' => 'posts']) }}"
               class="px-3 py-1.5 rounded-lg font-semibold transition {{ $tab === 'posts' ? 'bg-primary-800 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Discussions ({{ $posts->count() }})
            </a>
            <a href="{{ route('search.index', ['q' => $query, 'tab' => 'resources']) }}"
               class="px-3 py-1.5 rounded-lg font-semibold transition {{ $tab === 'resources' ? 'bg-primary-800 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Materials ({{ $resources->count() }})
            </a>
            <a href="{{ route('search.index', ['q' => $query, 'tab' => 'users']) }}"
               class="px-3 py-1.5 rounded-lg font-semibold transition {{ $tab === 'users' ? 'bg-primary-800 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Students & Faculty ({{ $users->count() }})
            </a>
        </div>

        <div class="space-y-8">
            {{-- Discussions Results --}}
            @if(in_array($tab, ['all', 'posts']) && $posts->isNotEmpty())
                <div>
                    <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Forum Discussions</h2>
                    <div class="space-y-2">
                        @foreach($posts as $post)
                            <x-card class="hover:border-primary-200 border border-transparent">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <x-tag :color="$post->category->color" size="sm">{{ $post->category->name }}</x-tag>
                                            <span class="text-xs text-gray-400">by {{ $post->user->name }} &middot; {{ $post->created_at->diffForHumans() }}</span>
                                        </div>
                                        <a href="{{ route('posts.show', $post) }}" class="font-bold text-gray-900 hover:text-primary-800 text-sm block truncate">
                                            {{ $post->title }}
                                        </a>
                                    </div>
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-primary-50 text-primary-800 flex-shrink-0">
                                        {{ $post->replies_count }} replies
                                    </span>
                                </div>
                            </x-card>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Resources Results --}}
            @if(in_array($tab, ['all', 'resources']) && $resources->isNotEmpty())
                <div>
                    <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Academic Materials</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($resources as $res)
                            <x-card class="hover:border-primary-200 border border-transparent">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <span class="text-[10px] font-bold uppercase px-1.5 py-0.5 rounded bg-gray-100 text-gray-600">
                                            {{ $res->file_type }}
                                        </span>
                                        <a href="{{ route('resources.show', $res) }}" class="font-bold text-sm text-gray-900 hover:text-primary-800 block truncate mt-1">
                                            {{ $res->title }}
                                        </a>
                                        <p class="text-xs text-gray-400 mt-1">{{ $res->downloads }} downloads &middot; {{ $res->file_size_human }}</p>
                                    </div>
                                    <a href="{{ route('resources.download', $res) }}" class="p-2 text-primary-800 hover:bg-primary-50 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                    </a>
                                </div>
                            </x-card>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Students / Faculty Results --}}
            @if(in_array($tab, ['all', 'users']) && $users->isNotEmpty())
                <div>
                    <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Students & Faculty</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($users as $u)
                            <x-card class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary-800 text-white flex items-center justify-center font-bold text-xs uppercase flex-shrink-0">
                                    {{ substr($u->name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-sm text-gray-900 truncate">{{ $u->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $u->department ?? 'UNILAK' }}</p>
                                    <span class="text-[10px] font-bold uppercase text-accent-600">{{ $u->role }}</span>
                                </div>
                            </x-card>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($posts->isEmpty() && $resources->isEmpty() && $users->isEmpty())
                <x-card class="text-center py-12">
                    <div class="text-3xl mb-2">🔍</div>
                    <h3 class="font-bold text-gray-900">No results found for "{{ $query }}"</h3>
                    <p class="text-xs text-gray-500 mt-1">Try different keywords or check spelling.</p>
                </x-card>
            @endif
        </div>
    @else
        <x-card class="text-center py-12">
            <div class="text-3xl mb-2">🔎</div>
            <h3 class="font-bold text-gray-900">Search the UNILAK Student Academic Platform</h3>
            <p class="text-xs text-gray-500 mt-1">Enter at least 2 characters to search across questions, lecture notes, and fellow students.</p>
        </x-card>
    @endif
</x-layouts.app>

