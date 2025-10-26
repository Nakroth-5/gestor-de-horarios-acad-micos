@props([
    'label' => '',
    'editing' => null,
    'name' => '',
    'type' => 'text',
    'placeholder' => '',
    'required' => false,
    'wireModel' => null,
    'readonly' => false,
    'min' => 0,
    'max' => 155555555555555,
    'step' => 0.1
])

<div {{ $attributes->merge(['class' => '']) }}>
    <x-input-label for="{{ $name }}">
        {{ $slot }}
        {{ $label }}
    </x-input-label>

    <x-text-input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->whereStartsWith('wire:model') }}
        :required="$required"
        :readonly="$readonly"
        min="{{ $min }}"
        max="{{ $max }}"
        step="{{ $step }}"
        class="{{ $editing ? 'cursor-not-allowed opacity-75' : '' }}"
    />
    <x-input-error class="mt-2" :messages="$errors->get('{{ $name }}')"/>
</div>
