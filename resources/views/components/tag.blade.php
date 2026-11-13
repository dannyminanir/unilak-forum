@props([
    'color' => null,
    'href'  => null,
    'size'  => 'md',
])

@php
$sizeClasses = [
    'sm' => 'px-2 py-0.5 text-xs font-medium',
    'md' => 'px-2.5 py-1 text-xs font-semibold',
    'lg' => 'px-3 py-1.5 text-sm font-semibold',
][$size] ?? 'px-2.5 py-1 text-xs font-semibold';

$defaultBg = 'bg-primary-50 text-primary-800 border border-primary-200';
$styleAttr = $color ? "background-color: {$color}15; color: {$color}; border-color: {$color}30;" : '';
@endphp

@if($href)
    <a href="{{ $href }}"
       style="{{ $styleAttr }}"
       {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 rounded-full transition hover:opacity-85 {$sizeClasses} " . ($color ? 'border' : $defaultBg)]) }}>
        {{ $slot }}
    </a>
@else
    <span style="{{ $styleAttr }}"
          {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 rounded-full {$sizeClasses} " . ($color ? 'border' : $defaultBg)]) }}>
        {{ $slot }}
    </span>
@endif

