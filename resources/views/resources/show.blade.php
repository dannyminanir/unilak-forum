<x-layouts.app>
    <x-slot name="title">{{ $resource->title }}</x-slot>

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-gray-500 mb-4">
        <a href="{{ route('resources.index') }}" class="hover:text-primary-800">Resources</a>
        <span>&rsaquo;</span>
        <a href="{{ route('categories.show', $resource->category) }}" class="hover:text-primary-800 font-medium text-gray-700">
            {{ $resource->category->name }}
        </a>
        <span>&rsaquo;</span>
        <span class="text-gray-400 truncate max-w-xs">{{ $resource->title }}</span>
    </nav>

    <div class="max-w-3xl">
        <x-card>
            <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-5 mb-5">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <x-tag :color="$resource->category->color" size="sm">
                            {{ $resource->category->icon }} {{ $resource->category->name }}
                        </x-tag>
                        <span class="text-xs uppercase font-extrabold px-2 py-0.5 rounded bg-gray-100 text-gray-700">
                            {{ $resource->file_type }}
                        </span>
                    </div>
                    <h1 class="text-xl font-bold text-gray-900 leading-snug">{{ $resource->title }}</h1>
                    <p class="text-xs text-gray-400 mt-1">
                        Uploaded by <strong class="text-gray-700">{{ $resource->user->name }}</strong> &middot; {{ $resource->created_at->format('M d, Y') }}
                    </p>
                </div>

                @can('delete', $resource)
                    <form method="POST" action="{{ route('resources.destroy', $resource) }}" onsubmit="return confirm('Delete this resource?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-semibold text-red-600 hover:underline">
                            Delete
                        </button>
                    </form>
                @endcan
            </div>

            @if($resource->description)
                <div class="text-sm text-gray-700 mb-6 leading-relaxed whitespace-pre-line">
                    {{ $resource->description }}
                </div>
            @endif

            {{-- Download Box --}}
            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="w-12 h-12 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-xl shadow-sm">
                        📄
                    </span>
                    <div>
                        <p class="text-xs font-semibold text-gray-900 truncate max-w-xs">{{ $resource->title }}.{{ $resource->file_type }}</p>
                        <p class="text-[11px] text-gray-500 mt-0.5">{{ $resource->file_size_human }} &middot; {{ $resource->downloads }} total downloads</p>
                    </div>
                </div>

                <a href="{{ route('resources.download', $resource) }}">
                    <x-button variant="secondary" class="w-full sm:w-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download File
                    </x-button>
                </a>
            </div>
        </x-card>
    </div>
</x-layouts.app>

