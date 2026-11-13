@props([
    'model',         // Post or Reply
    'route',         // route to post vote
    'userVote' => null, // Current user's vote (+1, -1, or null)
    'orientation' => 'vertical', // vertical | horizontal
])

@php
$userVal = $userVote ? $userVote->value : 0;
$score = (int) ($model->votes_count ?? 0);
@endphp

<div class="flex {{ $orientation === 'vertical' ? 'flex-col items-center gap-1' : 'items-center gap-2' }}">
    {{-- Upvote Form --}}
    <form method="POST" action="{{ $route }}">
        @csrf
        <input type="hidden" name="value" value="1">
        <button type="submit"
                title="Upvote"
                class="p-1.5 rounded-lg transition {{ $userVal === 1 ? 'bg-accent-100 text-accent-600 font-bold' : 'text-gray-400 hover:bg-gray-100 hover:text-accent-500' }}">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </form>

    {{-- Score Display --}}
    <span class="text-xs font-bold {{ $score > 0 ? 'text-accent-600' : ($score < 0 ? 'text-red-500' : 'text-gray-600') }}">
        {{ $score }}
    </span>

    {{-- Downvote Form --}}
    <form method="POST" action="{{ $route }}">
        @csrf
        <input type="hidden" name="value" value="-1">
        <button type="submit"
                title="Downvote"
                class="p-1.5 rounded-lg transition {{ $userVal === -1 ? 'bg-red-100 text-red-600 font-bold' : 'text-gray-400 hover:bg-gray-100 hover:text-red-500' }}">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </form>
</div>

