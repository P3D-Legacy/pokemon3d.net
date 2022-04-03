<x-modal>
    <x-slot name="title">
        @lang('Leave a comment')
    </x-slot>

    <x-slot name="content">
        <x-text-area id="body" name="body" class="block w-full mt-1" placeholder="{{ __('Leave a comment') }}" autofocus wire:model.defer="body"></x-text-area>
        <span class="text-xs text-gray-400">@lang('Min characters'): 10 &middot; @lang('Max characters'): 255</span>
        <x-jet-input-error for="body" class="mt-2" />
    </x-slot>

    <x-slot name="buttons">
        <x-jet-button wire:click="save" wire:loading.attr="disabled">
            {{ __('Save') }}
        </x-jet-button>
        <x-jet-secondary-button wire:click="$emit('closeModal')">
            {{ __('Cancel') }}
        </x-jet-secondary-button>
    </x-slot>
</x-modal>
