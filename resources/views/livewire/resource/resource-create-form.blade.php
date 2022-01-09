<div>
    <x-jet-form-section submit="save">
        <x-slot name="title">
            {{ __('Create a Resource') }}
        </x-slot>

        <x-slot name="description">
            {{ __('*insert some description here*') }}
        </x-slot>

        <x-slot name="form">

            <div class="col-span-6">
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" type="text" wire:model.defer="name" class="block w-full mt-1" placeholder="My Resource Pack" autofocus />
                <x-jet-input-error for="name" class="mt-2" />
            </div>

            <div class="col-span-6">
                <x-jet-label for="description" value="{{ __('Description') }}" />
                <textarea id="easyMDE" wire:model.defer="description"></textarea>
                <x-jet-input-error for="description" class="mt-2"/>
            </div>

        </x-slot>

        <x-slot name="actions">
            <x-jet-button wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-jet-button>
        </x-slot>
    </x-jet-form-section>
</div>
