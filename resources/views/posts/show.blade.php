<x-layouts.app>
    <x-slot name="title">{{ $post->title }}</x-slot>

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-gray-500 mb-4">
        <a href="{{ route('categories.index') }}" class="hover:text-primary-800">Categories</a>
        <span>&rsaquo;</span>
        <a href="{{ route('categories.show', $post->category) }}" class="hover:text-primary-800 font-medium text-gray-700">
            {{ $post->category->name }}
        </a>
        <span>&rsaquo;</span>
        <span class="text-gray-400 truncate max-w-xs">{{ $post->title }}</span>
    </nav>

    {{-- Main Post Card with Left Voting Column --}}
    <x-card class="mb-6">
        <div class="flex items-start gap-5">
            {{-- Voting Column --}}
            <div class="flex-shrink-0 pt-1">
                <x-vote-box :model="$post" :route="route('posts.vote', $post)" :userVote="$userVote" />
            </div>

            {{-- Post Content Area --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-4 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-full bg-primary-800 text-white flex items-center justify-center font-bold text-sm uppercase">
                            {{ substr($post->user->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h1 class="text-xl font-bold text-gray-900 leading-snug">{{ $post->title }}</h1>
                            </div>
                            <div class="flex items-center gap-2 mt-1 flex-wrap text-xs text-gray-500">
                                <a href="{{ route('profile.show', $post->user) }}" class="font-medium text-gray-800 hover:text-primary-800">
                                    {{ $post->user->name }}
                                </a>
                                {{-- Reputation Badge --}}
                                @php $badge = $post->user->reputation_badge; @endphp
                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded border {{ $badge['color'] }}">
                                    {{ $badge['icon'] }} {{ $badge['title'] }} ({{ $post->user->reputation }} pts)
                                </span>
                                <span>&middot; {{ $post->created_at->format('M d, Y \a\t H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Badges & Bookmark Button --}}
                    <div class="flex items-center gap-2 flex-shrink-0">
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

                        {{-- Bookmark Toggle --}}
                        <form method="POST" action="{{ route('posts.save', $post) }}">
                            @csrf
                            <button type="submit"
                                    title="{{ $isSaved ? 'Remove Bookmark' : 'Bookmark Discussion' }}"
                                    class="p-2 rounded-lg border transition {{ $isSaved ? 'bg-accent-50 border-accent-300 text-accent-600' : 'border-gray-200 text-gray-400 hover:text-gray-700 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="{{ $isSaved ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Post Content --}}
                <div class="prose max-w-none text-gray-800 text-sm leading-relaxed whitespace-pre-line mb-6">
                    {{ $post->content }}
                </div>

                {{-- Tags & Controls --}}
                <div class="flex items-center justify-between border-t border-gray-100 pt-4 flex-wrap gap-3">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        @foreach($post->tags as $t)
                            <x-tag :href="route('tags.show', $t)" size="sm">#{{ $t->name }}</x-tag>
                        @endforeach
                    </div>

                    <div class="flex items-center gap-3 text-xs" x-data="{ reportModal: false }">
                        {{-- Moderator Controls: Pin & Lock --}}
                        @can('pin', $post)
                            <form method="POST" action="{{ route('posts.pin', $post) }}">
                                @csrf
                                <button type="submit" class="font-semibold text-accent-600 hover:underline">
                                    {{ $post->is_pinned ? 'Unpin' : '📌 Pin Topic' }}
                                </button>
                            </form>
                        @endcan

                        @can('lock', $post)
                            <form method="POST" action="{{ route('posts.lock', $post) }}">
                                @csrf
                                <button type="submit" class="font-semibold text-gray-600 hover:underline">
                                    {{ $post->is_closed ? '🔓 Reopen' : '🔒 Lock' }}
                                </button>
                            </form>
                        @endcan

                        @can('update', $post)
                            <a href="{{ route('posts.edit', $post) }}" class="font-semibold text-primary-800 hover:underline">
                                Edit
                            </a>
                        @endcan

                        @can('delete', $post)
                            <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Delete this discussion?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-semibold text-red-600 hover:underline">
                                    Delete
                                </button>
                            </form>
                        @endcan

                        {{-- Report Button --}}
                        <button @click="reportModal = true" class="text-gray-400 hover:text-red-500 font-medium">
                            Flag / Report
                        </button>

                        {{-- Report Modal --}}
                        <div x-show="reportModal" x-cloak
                             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                            <div @click.outside="reportModal = false" class="bg-white rounded-xl shadow-xl max-w-sm w-full p-5 text-left">
                                <h3 class="font-bold text-gray-900 text-sm mb-2">Report this Discussion</h3>
                                <p class="text-xs text-gray-500 mb-3">Help maintain academic integrity and a respectful community.</p>

                                <form method="POST" action="{{ route('posts.report', $post) }}" class="space-y-3">
                                    @csrf
                                    <div class="space-y-1.5 text-xs text-gray-700">
                                        <label class="flex items-center gap-2">
                                            <input type="radio" name="reason" value="Academic Dishonesty / Cheating" required class="text-primary-800">
                                            Academic Dishonesty / Cheating
                                        </label>
                                        <label class="flex items-center gap-2">
                                            <input type="radio" name="reason" value="Spam / Advertisements">
                                            Spam / Advertisements
                                        </label>
                                        <label class="flex items-center gap-2">
                                            <input type="radio" name="reason" value="Harassment / Inappropriate Language">
                                            Harassment / Inappropriate Language
                                        </label>
                                        <label class="flex items-center gap-2">
                                            <input type="radio" name="reason" value="Incorrect Academic Information">
                                            Incorrect Academic Information
                                        </label>
                                    </div>
                                    <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                                        <button type="button" @click="reportModal = false" class="text-xs text-gray-500 px-3 py-1.5">Cancel</button>
                                        <x-button type="submit" variant="danger" class="!py-1 !px-3 !text-xs">Submit Report</x-button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-card>

    {{-- Replies Section --}}
    <div x-data="replyApp()" x-init="init()">

        {{-- Reply count header --}}
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                Replies
                <span id="replies-count-badge"
                      class="text-xs font-semibold px-2 py-0.5 rounded-full bg-primary-100 text-primary-800">
                    {{ $replies->total() }}
                </span>
            </h2>
        </div>

        {{-- Existing server-rendered replies --}}
        <div id="replies-list" class="space-y-4 mb-8">
            @forelse($replies as $reply)
                @php
                $replyVote = auth()->check() ? auth()->user()->hasVotedOn($reply) : null;
                $replyBadge = $reply->user->reputation_badge;
                @endphp
                <div id="reply-{{ $reply->id }}"
                     x-data="{ editing: false, reportModal: false }"
                     class="rounded-xl border transition {{ $reply->is_best_answer ? 'border-green-400 bg-green-50/40 ring-1 ring-green-300' : 'border-gray-100 bg-white shadow-sm' }} p-5">

                    <div class="flex items-start gap-4">
                        {{-- Reply Vote Box --}}
                        <div class="flex-shrink-0 pt-0.5">
                            <x-vote-box :model="$reply" :route="route('replies.vote', $reply)" :userVote="$replyVote" />
                        </div>

                        <div class="flex-1 min-w-0">
                            {{-- Reply Header --}}
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center font-bold text-xs uppercase">
                                        {{ substr($reply->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('profile.show', $reply->user) }}" class="font-semibold text-sm text-gray-900 hover:text-primary-800">
                                                {{ $reply->user->name }}
                                            </a>
                                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded border {{ $replyBadge['color'] }}">
                                                {{ $replyBadge['icon'] }} {{ $replyBadge['title'] }}
                                            </span>
                                            @if($reply->is_best_answer)
                                                <span class="inline-flex items-center gap-1 text-xs font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded-full">
                                                    ✓ Best Answer
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-[11px] text-gray-400">{{ $reply->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center gap-2">
                                    @can('markBestAnswer', $reply)
                                        <form method="POST" action="{{ route('replies.best-answer', $reply) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="text-xs font-semibold px-2.5 py-1 rounded-md border transition {{ $reply->is_best_answer ? 'border-green-400 bg-green-100 text-green-800' : 'border-gray-200 text-gray-600 hover:bg-gray-100' }}">
                                                {{ $reply->is_best_answer ? '✓ Best Answer' : 'Mark as Best Answer' }}
                                            </button>
                                        </form>
                                    @endcan

                                    @can('update', $reply)
                                        <button @click="editing = !editing" class="text-xs text-primary-800 hover:underline font-medium">
                                            <span x-text="editing ? 'Cancel' : 'Edit'"></span>
                                        </button>
                                    @endcan

                                    @can('delete', $reply)
                                        <form method="POST" action="{{ route('replies.destroy', $reply) }}" onsubmit="return confirm('Delete this reply?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 p-1" title="Delete reply">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endcan

                                    <button @click="reportModal = true" class="text-xs text-gray-400 hover:text-red-500 ml-1" title="Report reply">
                                        Flag
                                    </button>

                                    {{-- Reply Report Modal --}}
                                    <div x-show="reportModal" x-cloak
                                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                                        <div @click.outside="reportModal = false" class="bg-white rounded-xl shadow-xl max-w-sm w-full p-5 text-left">
                                            <h3 class="font-bold text-gray-900 text-sm mb-2">Report Reply</h3>
                                            <form method="POST" action="{{ route('replies.report', $reply) }}" class="space-y-3">
                                                @csrf
                                                <div class="space-y-1.5 text-xs text-gray-700">
                                                    <label class="flex items-center gap-2">
                                                        <input type="radio" name="reason" value="Spam / Offensive" required class="text-primary-800">
                                                        Spam / Offensive
                                                    </label>
                                                    <label class="flex items-center gap-2">
                                                        <input type="radio" name="reason" value="Incorrect Academic Answer">
                                                        Incorrect Academic Answer
                                                    </label>
                                                    <label class="flex items-center gap-2">
                                                        <input type="radio" name="reason" value="Harassment">
                                                        Harassment
                                                    </label>
                                                </div>
                                                <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                                                    <button type="button" @click="reportModal = false" class="text-xs text-gray-500 px-3 py-1.5">Cancel</button>
                                                    <x-button type="submit" variant="danger" class="!py-1 !px-3 !text-xs">Report</x-button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Static Content --}}
                            <div x-show="!editing" class="text-sm text-gray-800 leading-relaxed whitespace-pre-line">
                                {{ $reply->content }}
                            </div>

                            {{-- Inline Edit Form --}}
                            <div x-show="editing" x-cloak class="mt-3">
                                <form method="POST" action="{{ route('replies.update', $reply) }}" class="space-y-2">
                                    @csrf
                                    @method('PUT')
                                    <textarea name="content" rows="3" required
                                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 shadow-sm focus:border-primary-800 focus:outline-none focus:ring-1 focus:ring-primary-800">{{ $reply->content }}</textarea>
                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="editing = false" class="text-xs text-gray-500 hover:text-gray-700 px-2 py-1">Cancel</button>
                                        <x-button type="submit" variant="primary" class="!py-1 !px-3 !text-xs">Update Reply</x-button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div id="empty-state" class="rounded-xl border border-gray-100 bg-white shadow-sm text-center py-8">
                    <p class="text-xs text-gray-500">No replies yet. Be the first to answer!</p>
                </div>
            @endforelse

            {{ $replies->links() }}
        </div>

        {{-- AJAX Reply Form --}}
        @auth
            @if(! $post->is_closed)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                    <h3 class="font-bold text-gray-900 mb-3 text-sm">Post Your Answer or Reply</h3>

                    {{-- Success toast --}}
                    <div x-show="toast"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         x-cloak
                         class="mb-3 flex items-center gap-2 rounded-lg bg-green-50 border border-green-200 px-4 py-2.5 text-sm text-green-700 font-medium">
                        ✓ <span x-text="toast"></span>
                    </div>

                    {{-- Error message --}}
                    <div x-show="errorMsg"
                         x-cloak
                         class="mb-3 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-red-600">
                        <span x-text="errorMsg"></span>
                    </div>

                    <form @submit.prevent="submitReply" class="space-y-4">
                        @csrf
                        <div class="flex flex-col gap-1">
                            <textarea
                                x-model="content"
                                rows="4"
                                required
                                :disabled="submitting"
                                placeholder="Write a clear, helpful response to assist your fellow UNILAK student..."
                                class="block w-full rounded-lg border border-gray-300 bg-white p-3.5 text-sm text-gray-900 shadow-sm focus:border-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-800 disabled:opacity-60 disabled:cursor-not-allowed"
                            ></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    :disabled="submitting || content.trim().length < 3"
                                    class="inline-flex items-center gap-2 rounded-lg bg-primary-800 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-900 disabled:opacity-50 disabled:cursor-not-allowed transition">
                                <span x-show="submitting" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                <span x-text="submitting ? 'Posting…' : 'Submit Reply'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="rounded-xl border border-gray-200 bg-gray-50 text-center py-4">
                    <p class="text-xs text-gray-500 font-medium">🔒 This discussion is closed and no longer accepting replies.</p>
                </div>
            @endif
        @else
            <div class="rounded-xl border border-gray-200 bg-gray-50 text-center py-5">
                <p class="text-sm text-gray-500">
                    <a href="{{ route('login') }}" class="font-semibold text-primary-800 hover:underline">Log in</a> to post a reply.
                </p>
            </div>
        @endauth

    </div>{{-- end x-data="replyApp()" --}}

    {{-- Alpine.js AJAX Reply Logic --}}
    <script>
    function replyApp() {
        return {
            content:    '',
            submitting: false,
            toast:      '',
            errorMsg:   '',
            toastTimer: null,

            init() {},

            async submitReply() {
                if (this.submitting || this.content.trim().length < 3) return;

                this.submitting = true;
                this.toast      = '';
                this.errorMsg   = '';

                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                const url       = '{{ route('replies.store', $post) }}';

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept':       'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ content: this.content }),
                    });

                    if (res.status === 422) {
                        const data = await res.json();
                        const msgs = Object.values(data.errors || {}).flat();
                        this.errorMsg = msgs[0] || 'Validation failed.';
                        return;
                    }

                    if (!res.ok) {
                        this.errorMsg = 'Something went wrong. Please try again.';
                        return;
                    }

                    const reply = await res.json();

                    // Remove the "empty state" card if present
                    const emptyEl = document.getElementById('empty-state');
                    if (emptyEl) emptyEl.remove();

                    // Build and inject new reply card
                    const list = document.getElementById('replies-list');
                    const card = this._buildReplyCard(reply);
                    list.insertAdjacentHTML('beforeend', card);

                    // Scroll the new reply into view
                    const newEl = document.getElementById('reply-' + reply.id);
                    if (newEl) newEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

                    // Update the reply count badge
                    const badge = document.getElementById('replies-count-badge');
                    if (badge) badge.textContent = reply.replies_count;

                    // Show success toast and reset form
                    this.content   = '';
                    this.showToast('Your reply has been posted! 🎉');

                } catch (err) {
                    this.errorMsg = 'Network error. Please check your connection.';
                } finally {
                    this.submitting = false;
                }
            },

            showToast(msg) {
                this.toast = msg;
                clearTimeout(this.toastTimer);
                this.toastTimer = setTimeout(() => { this.toast = ''; }, 4000);
            },

            _buildReplyCard(r) {
                const userUrl  = r.user.url;
                const initials = r.user.initial;
                const name     = r.user.name;
                const badge    = r.user.badge;
                const content  = r.content.replace(/</g, '&lt;').replace(/>/g, '&gt;');
                const timeAgo  = r.created_at;

                const deleteBtn = r.can_delete ? `
                    <form method="POST" action="${r.delete_route}" onsubmit="return confirm('Delete this reply?')">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').content}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="text-xs text-red-500 hover:text-red-700 p-1" title="Delete reply">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>` : '';

                const bestBtn = r.can_mark_best ? `
                    <form method="POST" action="${r.best_answer_route}">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').content}">
                        <button type="submit" class="text-xs font-semibold px-2.5 py-1 rounded-md border border-gray-200 text-gray-600 hover:bg-gray-100 transition">
                            Mark as Best Answer
                        </button>
                    </form>` : '';

                return `
                <div id="reply-${r.id}"
                     class="rounded-xl border border-gray-100 bg-white shadow-sm p-5 animate-pulse-once">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 flex flex-col items-center gap-1 pt-0.5">
                            <span class="text-xs text-gray-400">0</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-primary-800 text-white flex items-center justify-center font-bold text-xs uppercase">
                                        ${initials}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <a href="${userUrl}" class="font-semibold text-sm text-gray-900 hover:text-primary-800">${name}</a>
                                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded border ${badge.color}">
                                                ${badge.icon} ${badge.title}
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-gray-400">${timeAgo}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    ${bestBtn}
                                    ${deleteBtn}
                                </div>
                            </div>
                            <div class="text-sm text-gray-800 leading-relaxed whitespace-pre-line">${content}</div>
                        </div>
                    </div>
                </div>`;
            },
        };
    }
    </script>
</x-layouts.app>
