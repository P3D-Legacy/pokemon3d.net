<x-form-section submit="save">
    <x-slot name="title">
        Forum Account
    </x-slot>

    <x-slot name="description">
        <span class="inline-block">{{ __('Link your account with your :account account.',  ['account' => 'Forum']) }}</span>
        <span class="inline-block mt-2">{{ __('Last Updated:') }} {{ $updated_at ?? trans('Never') }} &middot; {{ __('Last Verified:') }} {{ $verified_at ?? trans('Never') }}</span>
    </x-slot>

    <x-slot name="form">
        @if (!isset($verified_at))
            <div class="col-span-6 sm:col-span-4">
                <x-label for="username" value="{{ __('Username') }}" />
                <x-input id="username" type="text" class="block w-full mt-1" wire:model.live="username" />
                <x-input-error for="username" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" type="password" class="block w-full mt-1" wire:model.live="password" />
                <x-input-error for="password" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <label for="syncRegisterDate" class="flex items-center">
                    <x-checkbox id="syncRegisterDate" wire:model.live="syncRegisterDate" />
                    <span class="ml-2 text-sm text-slate-600 dark:text-slate-300">{{ __('Sync your registration date on the forum with this account') }}</span>
                </label>
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
