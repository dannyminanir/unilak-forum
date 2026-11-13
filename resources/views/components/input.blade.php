@props([
    'name',
    'label'       => '',
    'type'        => 'text',
    'placeholder' => '',
    'value'       => null,
    'required'    => false,
    'autofocus'   => false,
])

@php
$hasError = $errors->has($name);
$inputClass = 'block w-full rounded-lg border px-3.5 py-2.5 text-sm text-gray-900 shadow-sm
    placeholder:text-gray-400 focus:outline-none focus:ring-2 transition duration-150 '
    . ($hasError
        ? 'border-red-400 bg-red-50 focus:ring-red-400'
        : 'border-gray-300 bg-white focus:ring-primary-800 focus:border-primary-800');
@endphp

<div class="flex flex-col gap-1">
    @if($label)
        <label for="{{ $name }}" class="text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-0.5">*</span>
            @endif
        </label>
    @endif

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        placeholder="{{ $placeholder }}"
        value="{{ old($name, $value) }}"
        {{ $required ? 'required' : '' }}
        {{ $autofocus ? 'autofocus' : '' }}
        {{ $attributes->merge(['class' => $inputClass]) }}
    />

    @error($name)
        <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p>
    @enderror
</div>

