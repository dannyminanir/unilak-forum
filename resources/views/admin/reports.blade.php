<x-layouts.app>
    <x-slot name="title">Moderation & Reports — Admin Panel</x-slot>

    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Moderation & Platform Administration</h1>
            <p class="text-sm text-gray-500 mt-1">Review flagged student posts, replies, and community safety</p>
        </div>
    </x-slot>

    {{-- System Metrics Row --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-8">
        <x-card class="!p-4 text-center">
            <p class="text-xl font-bold text-gray-900">{{ $metrics['total_users'] }}</p>
            <p class="text-[11px] text-gray-500 font-medium">Students</p>
        </x-card>
        <x-card class="!p-4 text-center">
            <p class="text-xl font-bold text-gray-900">{{ $metrics['total_posts'] }}</p>
            <p class="text-[11px] text-gray-500 font-medium">Discussions</p>
        </x-card>
        <x-card class="!p-4 text-center">
            <p class="text-xl font-bold text-gray-900">{{ $metrics['total_replies'] }}</p>
            <p class="text-[11px] text-gray-500 font-medium">Replies</p>
        </x-card>
        <x-card class="!p-4 text-center">
            <p class="text-xl font-bold text-gray-900">{{ $metrics['total_resources'] }}</p>
            <p class="text-[11px] text-gray-500 font-medium">Materials</p>
        </x-card>
        <x-card class="!p-4 text-center border-l-4 border-l-red-500">
            <p class="text-xl font-bold text-red-600">{{ $metrics['pending_reports'] }}</p>
            <p class="text-[11px] text-gray-500 font-medium">Pending Reports</p>
        </x-card>
        <x-card class="!p-4 text-center border-l-4 border-l-green-500">
            <p class="text-xl font-bold text-green-600">{{ $metrics['resolved_reports'] }}</p>
            <p class="text-[11px] text-gray-500 font-medium">Resolved</p>
        </x-card>
    </div>

    {{-- Status Tabs --}}
    <div class="flex items-center gap-2 border-b border-gray-200 pb-3 mb-6 text-xs">
        <a href="{{ route('admin.reports', ['status' => 'pending']) }}"
           class="px-3 py-1.5 rounded-lg font-semibold transition {{ $status === 'pending' ? 'bg-primary-800 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Pending Reports ({{ $metrics['pending_reports'] }})
        </a>
        <a href="{{ route('admin.reports', ['status' => 'resolved']) }}"
           class="px-3 py-1.5 rounded-lg font-semibold transition {{ $status === 'resolved' ? 'bg-primary-800 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Resolved History
        </a>
    </div>

    {{-- Reports Table --}}
    <div class="space-y-4">
        @forelse($reports as $report)
            <x-card class="border border-gray-200">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-2 flex-wrap">
                            <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded {{ $report->status === 'pending' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                {{ $report->status }}
                            </span>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-gray-100 text-gray-700">
                                Target: {{ class_basename($report->reportable_type) }} #{{ $report->reportable_id }}
                            </span>
                            <span class="text-xs text-gray-400">
                                Reported by <strong>{{ $report->user->name }}</strong> &middot; {{ $report->created_at->diffForHumans() }}
                            </span>
                        </div>

                        {{-- Reason --}}
                        <div class="mb-3">
                            <span class="text-xs font-bold text-red-600">Reported Reason:</span>
                            <span class="text-xs font-medium text-gray-900 bg-red-50 px-2 py-1 rounded ml-1">{{ $report->reason }}</span>
                        </div>

                        {{-- Reported Content Snippet --}}
                        <div class="bg-gray-50 rounded-lg p-3 text-xs text-gray-700 border border-gray-200">
                            @if($report->reportable)
                                @if($report->reportable instanceof \App\Models\Post)
                                    <p class="font-bold text-gray-900 mb-1">Topic: {{ $report->reportable->title }}</p>
                                    <p class="line-clamp-3">{{ $report->reportable->content }}</p>
                                @elseif($report->reportable instanceof \App\Models\Reply)
                                    <p class="font-bold text-gray-900 mb-1">Reply on Topic: {{ $report->reportable->post->title ?? 'Post' }}</p>
                                    <p class="line-clamp-3">{{ $report->reportable->content }}</p>
                                @endif
                            @else
                                <span class="italic text-gray-400">Content already deleted.</span>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    @if($report->status === 'pending')
                        <div class="flex items-center md:flex-col gap-2 flex-shrink-0 text-xs">
                            @if($report->reportable)
                                <form method="POST" action="{{ route('admin.reports.delete', $report) }}" onsubmit="return confirm('Delete this reported content permanently?')">
                                    @csrf
                                    <x-button type="submit" variant="danger" class="!py-1.5 !px-3 !text-xs w-full">
                                        Delete Content
                                    </x-button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('admin.reports.dismiss', $report) }}">
                                @csrf
                                <x-button type="submit" variant="ghost" class="!py-1.5 !px-3 !text-xs w-full">
                                    Dismiss Report
                                </x-button>
                            </form>
                        </div>
                    @endif
                </div>
            </x-card>
        @empty
            <x-card class="text-center py-12">
                <div class="text-3xl mb-2">🛡️</div>
                <h3 class="font-bold text-gray-900">No {{ $status }} reports</h3>
                <p class="text-xs text-gray-500 mt-1">The UNILAK Student Academic Forum is running smoothly!</p>
            </x-card>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $reports->links() }}
    </div>
</x-layouts.app>

