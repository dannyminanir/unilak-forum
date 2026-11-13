<x-layouts.app>
    <x-slot name="title">Dashboard</x-slot>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Student Dashboard</h1>
                <p class="text-sm text-gray-500 mt-1">Welcome back to the UNILAK academic portal</p>
            </div>
            <a href="{{ route('posts.create') }}">
                <x-button variant="secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Ask a Question
                </x-button>
            </a>
        </div>
    </x-slot>

    {{-- Welcome banner --}}
    <div class="mb-8 bg-gradient-to-r from-primary-800 to-primary-700 rounded-2xl p-6 text-white shadow-md">
        <div class="flex items-center gap-4">
            <span class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-white/20 text-white font-black text-2xl">
                {{ substr($user->name, 0, 1) }}
            </span>
            <div>
                <h2 class="text-xl font-bold">Welcome, {{ $user->name }}! 👋</h2>
                <p class="text-primary-200 text-sm mt-0.5">{{ $user->department ?? 'UNILAK Student' }} &middot; {{ $user->student_id ?? 'Academic ID Pending' }}</p>
            </div>
            <div class="ml-auto hidden sm:block">
                @php
                $roleColors = [
                    'admin'     => 'bg-red-500 text-white',
                    'moderator' => 'bg-amber-400 text-gray-900',
                    'student'   => 'bg-accent-500 text-white',
                ];
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $roleColors[$user->role] ?? 'bg-accent-500 text-white' }}">
                    {{ $user->role }}
                </span>
            </div>
        </div>
    </div>

    {{-- Stats row --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <a href="{{ route('posts.index') }}" class="block group">
            <x-card class="flex items-center gap-4 hover:border-primary-300 transition duration-150 border border-transparent">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-primary-50 text-primary-800 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                    </svg>
                </span>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['discussions'] }}</p>
                    <p class="text-sm text-gray-500">Discussions</p>
                </div>
            </x-card>
        </a>

        <x-card class="flex items-center gap-4">
            <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-accent-50 text-accent-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
            </span>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['announcements'] }}</p>
                <p class="text-sm text-gray-500">Announcements</p>
            </div>
        </x-card>

        <x-card class="flex items-center gap-4">
            <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-green-50 text-green-700 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </span>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['resources'] }}</p>
                <p class="text-sm text-gray-500">Academic Resources</p>
            </div>
        </x-card>
    </div>

    {{-- Announcements Notice (if any) --}}
    @if($pinnedAnnouncements->isNotEmpty())
        <div class="mb-8 space-y-3">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Official Announcements</h3>
            @foreach($pinnedAnnouncements as $announcement)
                <div class="bg-amber-50 border-l-4 border-accent-500 rounded-r-xl p-4 shadow-sm">
                    <div class="flex items-start gap-3">
                        <span class="text-lg">📢</span>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm">{{ $announcement->title }}</h4>
                            <p class="text-xs text-gray-700 mt-1 whitespace-pre-line leading-relaxed">{{ Str::limit($announcement->content, 200) }}</p>
                            <span class="text-[11px] text-gray-400 mt-2 block">{{ $announcement->published_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Recent Discussions list --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-bold text-gray-900 text-base">Recent Discussions</h3>
            <a href="{{ route('posts.index') }}" class="text-xs font-semibold text-primary-800 hover:text-accent-500 transition">
                View all &rarr;
            </a>
        </div>

        @forelse($recentPosts as $post)
            <x-card class="hover:border-primary-200 transition duration-150 border border-transparent">
                <div class="flex items-center justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <x-tag :color="$post->category->color" size="sm">
                                {{ $post->category->name }}
                            </x-tag>
                            <span class="text-xs text-gray-400">by {{ $post->user->name }} &middot; {{ $post->created_at->diffForHumans() }}</span>
                        </div>
                        <a href="{{ route('posts.show', $post) }}" class="font-semibold text-gray-900 hover:text-primary-800 text-sm truncate block">
                            {{ $post->title }}
                        </a>
                    </div>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-primary-50 text-primary-800 flex-shrink-0">
                        {{ $post->replies_count ?? 0 }} replies
                    </span>
                </div>
            </x-card>
        @empty
            <x-card class="text-center py-8">
                <p class="text-xs text-gray-500">No discussions posted yet. Be the first to start a conversation!</p>
            </x-card>
        @endforelse
    </div>
</x-layouts.app>
