@props(['options' => []])

@php
    $options = array_merge([
                    'dateFormat' => 'Y-m-d H:i:S',
                    'enableTime' => true,
                    'time_24hr' => true,
                    ], $options);
@endphp

<div wire:ignore>
    <input
        x-data="{
             value: @entangle($attributes->wire('model')),
             instance: undefined,
             init() {
                 $watch('value', value => this.instance.setDate(value, false));
                 this.instance = flatpickr(this.$refs.input, {{ json_encode((object)$options) }});
             }
        }"
        x-ref="input"
        x-bind:value="value"
        type="text"
        {{ $attributes->merge(['class' => 'w-full h-10 px-3 mb-2 text-base text-slate-800 placeholder-slate-600 border rounded-lg focus:shadow-outline']) }}
    />
</div>
