<x-layouts.app>
    <x-slot name="title">Announcements</x-slot>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Official Campus Announcements</h1>
                <p class="text-sm text-gray-500 mt-1">Official communications from UNILAK administration and faculties</p>
            </div>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('announcements.create') }}">
                    <x-button variant="secondary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        New Announcement
                    </x-button>
                </a>
            @endif
        </div>
    </x-slot>

    {{-- Department filter pills --}}
    <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-2 border-b border-gray-200 text-xs">
        <a href="{{ route('announcements.index') }}"
           class="px-3 py-1.5 rounded-lg font-medium whitespace-nowrap transition {{ empty($selectedDepartment) ? 'bg-primary-800 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            All Departments
        </a>
        @foreach($departments as $dept)
            <a href="{{ route('announcements.index', ['department' => $dept]) }}"
               class="px-3 py-1.5 rounded-lg font-medium whitespace-nowrap transition {{ $selectedDepartment === $dept ? 'bg-primary-800 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                {{ $dept }}
            </a>
        @endforeach
    </div>

    {{-- Announcements List --}}
    <div class="space-y-4">
        @forelse($announcements as $item)
            <x-card class="{{ $item->is_pinned ? 'border-l-4 border-l-accent-500 ring-1 ring-accent-100 bg-amber-50/20' : '' }}">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                            @if($item->is_pinned)
                                <span class="text-xs font-bold text-accent-600 bg-accent-50 px-2.5 py-0.5 rounded-full">
                                    📌 Pinned
                                </span>
                            @endif

                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-primary-50 text-primary-800">
                                {{ $item->department ?? 'University-Wide' }}
                            </span>

                            <span class="text-xs text-gray-400">
                                by <strong class="text-gray-700">{{ $item->admin->name }}</strong> &middot; {{ $item->published_at->format('M d, Y') }}
                            </span>
                        </div>

                        <h2 class="text-base font-bold text-gray-900 mb-2">
                            {{ $item->title }}
                        </h2>

                        <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                            {{ $item->content }}
                        </div>
                    </div>

                    {{-- Admin Actions --}}
                    @if(auth()->user()->isAdmin())
                        <div class="flex items-center gap-2 flex-shrink-0 text-xs">
                            <a href="{{ route('announcements.edit', $item) }}" class="font-semibold text-primary-800 hover:underline">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('announcements.destroy', $item) }}" onsubmit="return confirm('Delete this announcement?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-semibold text-red-600 hover:underline">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </x-card>
        @empty
            <x-card class="text-center py-12">
                <div class="text-3xl mb-2">📢</div>
                <h3 class="font-semibold text-gray-900">No announcements posted</h3>
                <p class="text-xs text-gray-500 mt-1">Official communications from UNILAK will appear here.</p>
            </x-card>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $announcements->links() }}
    </div>
</x-layouts.app>

