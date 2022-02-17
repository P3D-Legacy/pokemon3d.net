<x-modal>
    <x-slot name="title">
        Category
    </x-slot>

    <x-slot name="content">
        <x-jet-label for="category.name" value="{{ __('Name') }}" />
        <x-jet-input id="category.name" type="text" class="block w-full mt-1" wire:model.defer="category.name" autocomplete="name" />
        <x-jet-input-error for="category.name" class="mt-2" />
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