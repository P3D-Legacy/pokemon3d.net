<x-jet-form-section submit="save">
    <x-slot name="title">
        <img src="{{ asset('img/gamejolt-logo-light-1x.png') }}" class="inline-block">
    </x-slot>

    <x-slot name="description">
        <span class="inline-block">{{ __('Link your account with GameJolt to be able to edit your skins for in-game use, and more features to come.') }}</span>
        <span class="inline-block mt-2">{{ __('Last Updated:') }} {{ $updated_at ?? 'Never.' }} &middot; {{ __('Last Verified:') }} {{ $verified_at ?? 'Never.' }}</span>
        <span class="inline-block mt-4 font-semibold">
            <a href="https://gamejolt.com/help/tokens" target="_blank" class="hover:text-green-700 hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>{{ __('What\'s my token?') }}
            </a>
        </span>
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="username" value="{{ __('Username') }}" />
            <x-jet-input id="username" type="text" class="block w-full mt-1" wire:model="username" />
            <x-jet-input-error for="username" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="token" value="{{ __('Token') }}" />
            <x-jet-input id="token" type="password" class="block w-full mt-1" wire:model="token" />
            <x-jet-input-error for="token" class="mt-2" />
        </div>

        
    </x-slot>

    <x-slot name="actions">
        
        <x-jet-action-message class="mr-3 text-green-500" on="saved">
            {{ __('Verified and saved.') }}
        </x-jet-action-message>

        @error('error')
            <span class="mr-3 text-red-500">{{ $message }}</span>
        @enderror

        @error('success')
            <span class="mr-3 text-green-500">{{ $message }}</span>
        @enderror

        <x-jet-button>
            {{ __('Save') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
