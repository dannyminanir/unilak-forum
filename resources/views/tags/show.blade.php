<x-layouts.app>
    <x-slot name="title">#{{ $tag->name }}</x-slot>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <span class="text-accent-500">#</span>{{ $tag->name }}
                </h1>
                <p class="text-sm text-gray-500 mt-1">All discussions tagged with #{{ $tag->name }}</p>
            </div>
            <a href="{{ route('posts.index') }}" class="text-xs font-semibold text-primary-800 hover:underline">
                &larr; Back to all discussions
            </a>
        </div>
    </x-slot>

    <div class="space-y-3">
        @forelse($posts as $post)
            <x-card class="hover:border-primary-200 transition duration-150 border border-transparent">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <x-tag :color="$post->category->color" size="sm">
                                {{ $post->category->name }}
                            </x-tag>
                            <span class="text-xs text-gray-400">
                                by {{ $post->user->name }} &middot; {{ $post->created_at->diffForHumans() }}
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

                    <div class="flex flex-col items-end text-right flex-shrink-0">
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-primary-50 text-primary-800">
                            {{ $post->replies_count }} replies
                        </span>
                        <span class="text-xs text-gray-400 mt-1">
                            {{ $post->votes_count }} votes
                        </span>
                    </div>
                </div>
            </x-card>
        @empty
            <x-card class="text-center py-8">
                <p class="text-xs text-gray-500">No discussions tagged with #{{ $tag->name }} yet.</p>
            </x-card>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $posts->links() }}
    </div>
</x-layouts.app>

