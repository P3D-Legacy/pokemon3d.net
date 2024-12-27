<x-modal>
    <x-slot name="title">
        @lang('Leave a comment')
    </x-slot>

    <x-slot name="content">
        <x-text-area id="body" name="body" class="block w-full mt-1" placeholder="{{ __('Leave a comment') }}" autofocus wire:model="body"></x-text-area>
        <span class="text-xs text-slate-400">@lang('Min characters'): 2 &middot; @lang('Max characters'): 255</span>
        <x-input-error for="body" class="mt-2" />
    </x-slot>

    <x-slot name="buttons">
        <x-button wire:click="save" wire:loading.attr="disabled">
            {{ __('Save') }}
        </x-button>
        <x-secondary-button wire:click="$dispatch('closeModal')">
            {{ __('Cancel') }}
        </x-secondary-button>
    </x-slot>
</x-modal>
