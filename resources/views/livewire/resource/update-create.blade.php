<x-modal>
    <x-slot name="title">
        Add resource update
    </x-slot>

    <x-slot name="content">
        <x-jet-label for="version" value="{{ __('Version') }}" />
        <x-jet-input id="version" type="text" class="block w-full mt-1" wire:model.defer="version" autocomplete="version" placeholder="1.2.3" />
        <x-jet-input-error for="version" class="mt-2" />

        <x-jet-label for="description" class="mt-4" value="{{ __('Description') }}" />
        <x-easy-mde name="description" wire:model.defer="description" :options="['hideIcons' => ['side-by-side','fullscreen',]]"></x-easy-mde>
        <x-jet-input-error for="description" class="mt-2" />
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