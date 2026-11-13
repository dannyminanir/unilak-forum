<x-layouts.app>
    <x-slot name="title">Notifications</x-slot>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Your Notifications</h1>
                <p class="text-sm text-gray-500 mt-1">Updates on your questions, replies, and academic discussions</p>
            </div>
            @if(auth()->user()->unreadNotifications->isNotEmpty())
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <x-button type="submit" variant="ghost" class="!text-xs">
                        Mark All as Read
                    </x-button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="space-y-3 max-w-3xl">
        @forelse($notifications as $n)
            @php $isUnread = $n->unread(); @endphp
            <a href="{{ route('notifications.show', $n->id) }}" class="block">
                <x-card class="transition hover:border-primary-300 {{ $isUnread ? 'bg-primary-50/30 border-primary-200 ring-1 ring-primary-100' : 'border border-transparent' }}">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 {{ $isUnread ? 'bg-primary-800 text-white' : 'bg-gray-100 text-gray-600' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm {{ $isUnread ? 'font-bold text-gray-900' : 'text-gray-700' }}">
                                {{ $n->data['message'] ?? 'New notification on your discussion.' }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ $n->created_at->diffForHumans() }}
                            </p>
                        </div>
                        @if($isUnread)
                            <span class="w-2.5 h-2.5 rounded-full bg-accent-500 flex-shrink-0 mt-1"></span>
                        @endif
                    </div>
                </x-card>
            </a>
        @empty
            <x-card class="text-center py-12">
                <div class="text-3xl mb-2">🔔</div>
                <h3 class="font-bold text-gray-900">No notifications yet</h3>
                <p class="text-xs text-gray-500 mt-1">When fellow students reply to your questions or solutions, you'll receive updates here.</p>
            </x-card>
        @endforelse

        {{ $notifications->links() }}
    </div>
</x-layouts.app>

