<x-layouts.app>
    <x-slot name="title">Saved Discussions</x-slot>

    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Your Bookmarked Discussions</h1>
        <p class="text-sm text-gray-500 mt-1">Easily access topics and answers you've saved for later study</p>
    </x-slot>

    <div class="space-y-3">
        @forelse($posts as $post)
            <x-card class="hover:border-primary-200 transition duration-150 border border-transparent">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                            <x-tag :color="$post->category->color" size="sm">
                                {{ $post->category->name }}
                            </x-tag>
                            <span class="text-xs text-gray-400">
                                by <strong class="text-gray-700">{{ $post->user->name }}</strong> &middot; {{ $post->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <a href="{{ route('posts.show', $post) }}" class="block">
                            <h2 class="text-base font-semibold text-gray-900 hover:text-primary-800 transition line-clamp-1">
                                {{ $post->title }}
                            </h2>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                                {{ Str::limit(strip_tags($post->content), 170) }}
                            </p>
                        </a>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <form method="POST" action="{{ route('posts.save', $post) }}">
                            @csrf
                            <button type="submit" class="text-xs font-semibold text-red-600 hover:underline">
                                Remove
                            </button>
                        </form>
                    </div>
                </div>
            </x-card>
        @empty
            <x-card class="text-center py-12">
                <div class="text-3xl mb-2">🔖</div>
                <h3 class="font-semibold text-gray-900">No saved discussions yet</h3>
                <p class="text-xs text-gray-500 mt-1">Click the bookmark icon on any discussion to save it here for quick reference.</p>
                <div class="mt-4">
                    <a href="{{ route('posts.index') }}">
                        <x-button variant="primary">Browse Discussions</x-button>
                    </a>
                </div>
            </x-card>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $posts->links() }}
    </div>
</x-layouts.app>

