@props(['padding' => true])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl shadow-sm ring-1 ring-gray-100 ' . ($padding ? 'p-6' : '')]) }}>
    {{ $slot }}
</div>

