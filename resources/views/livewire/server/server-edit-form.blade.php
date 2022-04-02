<div>
    <x-jet-form-section submit="save">
        <x-slot name="title">
            {{ __('Edit') . ': ' . $server->name }}
        </x-slot>

        <x-slot name="description">
            {{ __('Update your server info here.') }}
        </x-slot>

        <x-slot name="form">

            <div class="col-span-6">
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" type="text" wire:model.defer="name" class="block w-full mt-1" placeholder="My Server" autofocus />
                <x-jet-input-error for="name" class="mt-2" />
            </div>

            <div class="col-span-6">
                <x-jet-label for="host" value="{{ __('Hostname/IP Address') }}" />
                <x-jet-input id="host" type="text" wire:model.defer="host" class="block w-full mt-1" placeholder="meganium.pokemon3d.net" />
                <x-jet-input-error for="host" class="mt-2" />
            </div>

            <div class="col-span-6">
                <x-jet-label for="port" value="{{ __('Port') }}" />
                <x-jet-input id="port" type="text" wire:model.defer="port" class="block w-full mt-1" placeholder="15124" />
                <x-jet-input-error for="port" class="mt-2" />
            </div>

            <div class="col-span-6">
                <x-jet-label for="description" value="{{ __('Description') }}" />
                <textarea id="description" wire:model.defer="description" class="block w-full mt-1 px-3 py-1.5 border-gray-300 rounded-md shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50 dark:bg-black dark:text-white dark:border-gray-900" placeholder="This is a server for me and my friends."></textarea>
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
