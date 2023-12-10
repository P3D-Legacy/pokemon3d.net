<x-form-section submit="save">
    <x-slot name="title">
        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
        </svg>Forum Account
    </x-slot>

    <x-slot name="description">
        Login with your forum account.
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6">
            <x-label for="username" value="{{ __('Email or Username') }}" />
            <x-input id="username" type="text" class="block w-full mt-1" wire:model="username" />
            <x-input-error for="username" class="mt-2" />
        </div>

        <div class="col-span-6">
            <x-label for="password" value="{{ __('Password') }}" />
            <x-input id="password" type="password" class="block w-full mt-1" wire:model="password" />
            <x-input-error for="password" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">

        @error('error')
            <span class="mr-3 text-red-500">{{ $message }}</span>
        @enderror

        <x-button>
            {{ __('Log in') }}
        </x-button>
    </x-slot>
</x-form-section>
