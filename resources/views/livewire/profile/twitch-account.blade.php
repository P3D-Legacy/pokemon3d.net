<x-jet-action-section>
    <x-slot name="title">
        <svg class="inline-block w-auto h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
            <path d="M5.7 0L1.4 10.985V55.88h15.284V64h8.597l8.12-8.12h12.418l16.716-16.716V0H5.7zm51.104 36.3L47.25 45.85H31.967l-8.12 8.12v-8.12H10.952V5.73h45.85V36.3zM47.25 16.716v16.716h-5.73V16.716h5.73zm-15.284 0v16.716h-5.73V16.716h5.73z" fill="currentColor" fill-rule="evenodd"/>
        </svg>
        Twitch
    </x-slot>

    <x-slot name="description">
        <span class="inline-block">{{ __('Link your account with your :account account.',  ['account' => 'Twitch']) }}</span>
        <span class="inline-block mt-2">{{ __('Last Updated:') }} {{ $updated_at ?? 'Never.' }} &middot; {{ __('Last Verified:') }} {{ $verified_at ?? 'Never.' }}</span>
    </x-slot>

    <x-slot name="content">
        @if (!isset($username))
            <a href="{{ route('twitch.login') }}" class="flex items-center justify-center w-full px-4 py-3 text-sm font-semibold tracking-widest text-white uppercase transition border border-transparent rounded-md bg-violet-600 hover:bg-violet-700 active:bg-violet-400 focus:outline-none focus:border-violet-900 focus:ring focus:ring-violet-300 disabled:opacity-25">
                <svg class="inline-block w-auto h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                    <path d="M5.7 0L1.4 10.985V55.88h15.284V64h8.597l8.12-8.12h12.418l16.716-16.716V0H5.7zm51.104 36.3L47.25 45.85H31.967l-8.12 8.12v-8.12H10.952V5.73h45.85V36.3zM47.25 16.716v16.716h-5.73V16.716h5.73zm-15.284 0v16.716h-5.73V16.716h5.73z" fill="currentColor" fill-rule="evenodd"/>
                </svg>
                Connect with Twitch
            </a>
        @else
            <div class="flex mb-5 text-gray-600 bg-white rounded shadow dark:text-gray-200 dark:bg-black w-max">
                <div class="self-center p-2 pr-1">
                    <img data="picture" class="w-12 h-12 rounded-full" src="{{ $avatar }}" alt="{{ $name }}" />
                </div>
                <div class="self-center w-64 p-2">
                    {{ $name }}
                </div>
            </div>

            <div class="mt-4">
                <x-jet-danger-button wire:click="remove" wire:loading.attr="disabled">
                    {{ __('Remove Association') }}
                </x-jet-danger-button>
            </div>
        @endif
    </x-slot>

</x-jet-form-section>
