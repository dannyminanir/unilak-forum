@props([
    'variant' => 'primary',
    'type'    => 'button',
    'full'    => false,
])

@php
$base = 'inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm transition duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed';

$variants = [
    'primary'   => 'bg-primary-800 hover:bg-primary-700 text-white focus:ring-primary-800',
    'secondary' => 'bg-accent-500 hover:bg-accent-600 text-white focus:ring-accent-500',
    'danger'    => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-600',
    'ghost'     => 'bg-transparent border border-primary-800 text-primary-800 hover:bg-primary-50 focus:ring-primary-800',
    'link'      => 'bg-transparent text-primary-800 hover:underline focus:ring-primary-800 px-0 py-0',
];

$classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ($full ? ' w-full' : '');
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>

