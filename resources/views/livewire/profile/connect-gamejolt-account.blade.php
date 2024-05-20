<x-form-section submit="save">
    <x-slot name="title">
        <img src="{{ asset('img/gamejolt-logo-light-1x.png') }}" class="inline-block">
    </x-slot>

    <x-slot name="description">
        <span class="inline-block">{{ __('Link your account with Game Jolt to be able to edit your skins for in-game use, and for more features to come.') }}</span>
        <span class="inline-block mt-2">{{ __('Last Updated:') }} {{ $updated_at ?? trans('Never') }} &middot; {{ __('Last Verified:') }} {{ $verified_at ?? trans('Never') }}</span>
        <span class="inline-block mt-4 font-semibold">
            <a href="https://gamejolt.com/help/tokens" target="_blank" class="hover:text-green-700 hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>{{ __("What's my token?") }}
            </a>
        </span>
    </x-slot>

    <x-slot name="form">
        @if (!isset($verified_at))
            <div class="col-span-6 sm:col-span-4">
                <x-label for="username" value="{{ __('Username') }}" />
                <x-input id="username" type="text" class="block w-full mt-1" wire:model.live="username" />
                <x-input-error for="username" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <x-label for="token" value="{{ __('Token') }}" />
                <x-input id="token" type="password" class="block w-full mt-1" wire:model.live="token" />
                <x-input-error for="token" class="mt-2" />
            </div>
        @else
            <div class="flex mb-5 text-slate-600 bg-white rounded shadow dark:text-slate-200 dark:bg-black w-max">
                <div class="self-center w-64 p-2">
                    {{ $username }}
                </div>
            </div>
        @endif
    </x-slot>

    <x-slot name="actions">

        <x-action-message class="mr-3 text-green-500" on="saved">
            {{ __('Verified and saved.') }}
        </x-action-message>

        @error('error')
            <span class="mr-3 text-red-500">{{ $message }}</span>
        @enderror

        @error('success')
            <span class="mr-3 text-green-500">{{ $message }}</span>
        @enderror

        @if (!isset($verified_at))
            <x-button>
                {{ __('Save') }}
            </x-button>
        @else
            <x-danger-button wire:click="remove" wire:loading.attr="disabled">
                {{ __('Remove Association') }}
            </x-danger-button>
        @endif
    </x-slot>
</x-form-section>
