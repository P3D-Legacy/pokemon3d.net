<x-jet-form-section submit="save">
    <x-slot name="title">
        Forum Account
    </x-slot>

    <x-slot name="description">
        <span class="inline-block">{{ __('Link your account with the forum account.') }}</span>
        <span class="inline-block mt-1">{{ __('Last Updated:') }} {{ $updated_at ?? 'Never.' }} &middot; {{ __('Last Verified:') }} {{ $verified_at ?? 'Never.' }}</span>
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="username" value="{{ __('Username') }}" />
            <x-jet-input id="username" type="text" class="block w-full mt-1" wire:model="username" />
            <x-jet-input-error for="username" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="password" value="{{ __('Password') }}" />
            <x-jet-input id="password" type="password" class="block w-full mt-1" wire:model="password" />
            <x-jet-input-error for="password" class="mt-2" />
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
