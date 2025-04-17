<div>
    <x-form-section submit="save">
        <x-slot name="title">
            {{ __('Add a server') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Do you want your server to be listed for other players to see?') }}
            {{--
                <div class="px-4 py-3 my-3 text-sm leading-normal text-blue-600 bg-blue-100 border rounded-lg border-blue-600/25 dark:text-blue-100 dark:bg-blue-600 dark:border-blue-100/25" role="alert">
                    <p>
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>{!! __('Need help to setup a own server? Check out <a href=":url" class="font-semibold underline">this guide</a>.', ['url' => '#']) !!}
                    </p>
                </div>
            --}}
        </x-slot>

        <x-slot name="form">

            <div class="col-span-6">
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" type="text" wire:model="name" class="block w-full mt-1" placeholder="My Server" autofocus />
                <x-input-error for="name" class="mt-2" />
            </div>

            <div class="col-span-6">
                <x-label for="host" value="{{ __('Hostname/IP Address') }}" />
                <x-input id="host" type="text" wire:model="host" class="block w-full mt-1" placeholder="meganium.pokemon3d.net" />
                <x-input-error for="host" class="mt-2" />
            </div>

            <div class="col-span-6">
                <x-label for="port" value="{{ __('Port') }}" />
                <x-input id="port" type="text" wire:model="port" class="block w-full mt-1" placeholder="15124" />
                <x-input-error for="port" class="mt-2" />
            </div>

            <div class="col-span-6">
                <x-label for="description" value="{{ __('Description') }}" />
                <textarea id="description" wire:model="description" class="block w-full mt-1 px-3 py-1.5 border-slate-300 rounded-md shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50 dark:bg-black dark:text-white dark:border-slate-900" placeholder="This is a server for me and my friends."></textarea>
                <x-input-error for="description" class="mt-2"/>
            </div>

        </x-slot>

        <x-slot name="actions">
            <x-button wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-button>
        </x-slot>
    </x-form-section>
</div>
