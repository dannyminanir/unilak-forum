<x-layouts.app>
    <x-slot name="title">Administration Panel</x-slot>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Administration Overview</h1>
                <p class="text-sm text-gray-500 mt-1">Platform management, academic records, and community moderation</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.reports') }}">
                    <x-button variant="danger" class="!text-xs">
                        🛡️ Reports ({{ $metrics['pending_reports'] }})
                    </x-button>
                </a>
                <a href="{{ route('announcements.create') }}">
                    <x-button variant="secondary" class="!text-xs">
                        📢 New Announcement
                    </x-button>
                </a>
            </div>
        </div>
    </x-slot>

    {{-- Admin Navigation Tabs --}}
    <div class="flex items-center gap-2 border-b border-gray-200 pb-3 mb-6 overflow-x-auto text-xs font-semibold">
        <a href="{{ route('admin.dashboard') }}" class="px-3 py-1.5 rounded-lg bg-primary-800 text-white">Overview</a>
        <a href="{{ route('admin.users.index') }}" class="px-3 py-1.5 rounded-lg text-gray-600 hover:bg-gray-100">Users ({{ $metrics['users'] }})</a>
        <a href="{{ route('admin.posts.index') }}" class="px-3 py-1.5 rounded-lg text-gray-600 hover:bg-gray-100">Discussions ({{ $metrics['posts'] }})</a>
        <a href="{{ route('admin.categories.index') }}" class="px-3 py-1.5 rounded-lg text-gray-600 hover:bg-gray-100">Categories ({{ $metrics['categories'] }})</a>
        <a href="{{ route('announcements.index') }}" class="px-3 py-1.5 rounded-lg text-gray-600 hover:bg-gray-100">Announcements</a>
        <a href="{{ route('admin.reports') }}" class="px-3 py-1.5 rounded-lg text-red-600 hover:bg-red-50">Reports ({{ $metrics['pending_reports'] }})</a>
    </div>

    {{-- Metrics Cards Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
        <x-card class="!p-5">
            <span class="text-xs text-gray-500 font-medium">Total Registered</span>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $metrics['users'] }}</p>
            <p class="text-[11px] text-gray-400 mt-1">{{ $metrics['students'] }} students &middot; {{ $metrics['admins'] }} admins</p>
        </x-card>

        <x-card class="!p-5">
            <span class="text-xs text-gray-500 font-medium">Discussions</span>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $metrics['posts'] }}</p>
            <p class="text-[11px] text-gray-400 mt-1">{{ $metrics['replies'] }} total replies</p>
        </x-card>

        <x-card class="!p-5">
            <span class="text-xs text-gray-500 font-medium">Study Materials</span>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $metrics['resources'] }}</p>
            <p class="text-[11px] text-gray-400 mt-1">Across {{ $metrics['categories'] }} categories</p>
        </x-card>

        <x-card class="!p-5 {{ $metrics['pending_reports'] > 0 ? 'border-red-300 ring-1 ring-red-100' : '' }}">
            <span class="text-xs text-gray-500 font-medium">Pending Reports</span>
            <p class="text-2xl font-bold {{ $metrics['pending_reports'] > 0 ? 'text-red-600' : 'text-gray-900' }} mt-1">
                {{ $metrics['pending_reports'] }}
            </p>
            <p class="text-[11px] text-gray-400 mt-1">{{ $metrics['resolved_reports'] }} resolved</p>
        </x-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Latest Students --}}
        <x-card>
            <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                <h2 class="font-bold text-sm text-gray-900">Recently Registered Students</h2>
                <a href="{{ route('admin.users.index') }}" class="text-xs text-primary-800 hover:underline">View All &rarr;</a>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($latestUsers as $u)
                    <div class="py-2.5 flex items-center justify-between text-xs">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $u->name }}</p>
                            <p class="text-gray-400">{{ $u->email }} &middot; {{ $u->department ?? 'No dept' }}</p>
                        </div>
                        <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded {{ $u->is_banned ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $u->is_banned ? 'Banned' : $u->role }}
                        </span>
                    </div>
                @endforeach
            </div>
        </x-card>

        {{-- Recent Discussions --}}
        <x-card>
            <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                <h2 class="font-bold text-sm text-gray-900">Latest Discussions</h2>
                <a href="{{ route('admin.posts.index') }}" class="text-xs text-primary-800 hover:underline">View All &rarr;</a>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($latestPosts as $p)
                    <div class="py-2.5 flex items-center justify-between text-xs">
                        <div class="min-w-0 pr-3">
                            <a href="{{ route('posts.show', $p) }}" class="font-semibold text-gray-900 hover:text-primary-800 truncate block">
                                {{ $p->title }}
                            </a>
                            <p class="text-gray-400">by {{ $p->user->name }} &middot; {{ $p->category->name }}</p>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-500 whitespace-nowrap">{{ $p->replies_count }} replies</span>
                    </div>
                @endforeach
            </div>
        </x-card>
    </div>
</x-layouts.app>

