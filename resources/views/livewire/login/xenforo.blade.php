<x-jet-form-section submit="save">
    <x-slot name="title">
        Forum Account
    </x-slot>

    <x-slot name="description">
        Login with your forum account.
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6">
            <x-jet-label for="username" value="{{ __('Email or Username') }}" />
            <x-jet-input id="username" type="text" class="mt-1 block w-full" wire:model="username" />
            <x-jet-input-error for="username" class="mt-2" />
        </div>

        <div class="col-span-6">
            <x-jet-label for="password" value="{{ __('Password') }}" />
            <x-jet-input id="password" type="password" class="mt-1 block w-full" wire:model="password" />
            <x-jet-input-error for="password" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">

        @error('error')
            <span class="mr-3 text-red-500">{{ $message }}</span>
        @enderror

        <x-jet-button>
            {{ __('Login') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
