@props([
    'label',
    'for',
    'error' => false,
])

<div {{ $attributes->merge(['class' => '']) }}>
    <x-label for="{{ $for }}">
        {{ $label }}
    </x-label>

    {{ $slot }}

    @if ($error)
        <x-error for="{{ $for }}" />
    @endif
</div>
