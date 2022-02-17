<x-modal>
    <x-slot name="title">
        Delete Category: <span class="font-bold text-red-400">{{ $category->name }}</span>
    </x-slot>

    <x-slot name="content">
        {{ __('Are you sure you want to delete this category?') }}
    </x-slot>

    <x-slot name="buttons">
        <x-jet-danger-button wire:click="delete" wire:loading.attr="disabled">
            {{ __('Yes') }}
        </x-jet-danger-button>
        <x-jet-button wire:click="$emit('closeModal')">
            {{ __('No, do not delete') }}
        </x-jet-button>
    </x-slot>
</x-modal>