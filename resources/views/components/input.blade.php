{{-- resources/views/components/input.blade.php --}}
@props([
'label' => '',
'name' => '',
'type' => 'text',
'value' => '',
'model' => null,
'required' => false,
'disabled' => false,
'placeholder' => '',
'options' => [], // For select inputs
'maxlength' => null,
'pattern' => null,
'title' => null,
])

@php
$inputValue = old($name, $model->{$name} ?? $value ?? '');
// Ensure we always have a string, not an array
if (is_array($inputValue)) {
$inputValue = '';
}
@endphp

<div>
    @if($label)
    <label class="block text-sm font-medium">{{ $label }}</label>
    @endif

    @if($type === 'select')
    <select
        name="{{ $name }}"
        class="w-full mt-1 border-gray-300 rounded"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes }}>
        @if(count($options) > 0)
        @foreach($options as $optionValue => $optionLabel)
        <option
            value="{{ $optionValue }}"
            @selected($inputValue==$optionValue)>
            {{ $optionLabel }}
        </option>
        @endforeach
        @endif
    </select>
    @else
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ $inputValue }}"
        class="w-full mt-1 border-gray-300 rounded"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $placeholder ? 'placeholder=' . $placeholder : '' }}
        {{ $maxlength ? 'maxlength=' . $maxlength : '' }}
        {{ $pattern ? 'pattern=' . $pattern : '' }}
        {{ $title ? 'title=' . $title : '' }}
        {{ $attributes }}>
    @endif

    @error($name)
    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
    @enderror
</div>