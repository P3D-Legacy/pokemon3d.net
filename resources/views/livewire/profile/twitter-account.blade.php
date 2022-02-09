<x-jet-action-section>
    <x-slot name="title">
        <svg class="inline-block w-auto h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 248 204">
            <g>
                <path d="M221.95,51.29c0.15,2.17,0.15,4.34,0.15,6.53c0,66.73-50.8,143.69-143.69,143.69v-0.04   C50.97,201.51,24.1,193.65,1,178.83c3.99,0.48,8,0.72,12.02,0.73c22.74,0.02,44.83-7.61,62.72-21.66   c-21.61-0.41-40.56-14.5-47.18-35.07c7.57,1.46,15.37,1.16,22.8-0.87C27.8,117.2,10.85,96.5,10.85,72.46c0-0.22,0-0.43,0-0.64   c7.02,3.91,14.88,6.08,22.92,6.32C11.58,63.31,4.74,33.79,18.14,10.71c25.64,31.55,63.47,50.73,104.08,52.76   c-4.07-17.54,1.49-35.92,14.61-48.25c20.34-19.12,52.33-18.14,71.45,2.19c11.31-2.23,22.15-6.38,32.07-12.26   c-3.77,11.69-11.66,21.62-22.2,27.93c10.01-1.18,19.79-3.86,29-7.95C240.37,35.29,231.83,44.14,221.95,51.29z" fill="currentColor"/>
            </g>
        </svg>
        Twitter
    </x-slot>

    <x-slot name="description">
        <span class="inline-block">{{ __('Link your account with your Twitter account.') }}</span>
        <span class="inline-block mt-2">{{ __('Last Updated:') }} {{ $updated_at ?? 'Never.' }} &middot; {{ __('Last Verified:') }} {{ $verified_at ?? 'Never.' }}</span>
    </x-slot>

    <x-slot name="content">
        @if (!isset($username))
            <a href="{{ route('twitter.login') }}" class="flex items-center justify-center w-full px-4 py-3 text-sm font-semibold tracking-widest text-white uppercase transition border border-transparent rounded-md bg-sky-600 hover:bg-sky-700 active:bg-sky-400 focus:outline-none focus:border-sky-900 focus:ring focus:ring-sky-300 disabled:opacity-25">
                <svg class="inline-block w-auto h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 248 204">
                    <g>
                        <path d="M221.95,51.29c0.15,2.17,0.15,4.34,0.15,6.53c0,66.73-50.8,143.69-143.69,143.69v-0.04   C50.97,201.51,24.1,193.65,1,178.83c3.99,0.48,8,0.72,12.02,0.73c22.74,0.02,44.83-7.61,62.72-21.66   c-21.61-0.41-40.56-14.5-47.18-35.07c7.57,1.46,15.37,1.16,22.8-0.87C27.8,117.2,10.85,96.5,10.85,72.46c0-0.22,0-0.43,0-0.64   c7.02,3.91,14.88,6.08,22.92,6.32C11.58,63.31,4.74,33.79,18.14,10.71c25.64,31.55,63.47,50.73,104.08,52.76   c-4.07-17.54,1.49-35.92,14.61-48.25c20.34-19.12,52.33-18.14,71.45,2.19c11.31-2.23,22.15-6.38,32.07-12.26   c-3.77,11.69-11.66,21.62-22.2,27.93c10.01-1.18,19.79-3.86,29-7.95C240.37,35.29,231.83,44.14,221.95,51.29z" fill="currentColor"/>
                    </g>
                </svg>
                Connect with Twitter
            </a>
        @else
            <div class="flex mb-5 text-gray-600 bg-white rounded shadow dark:text-gray-200 dark:bg-black w-max">
                <div class="self-center p-2 pr-1">
                    <img data="picture" class="w-12 h-12 rounded-full" src="{{ $avatar }}" alt="{{ $username }}" />
                </div>
                <div class="self-center w-64 p-2">
                    {{ $name }}
                    <div class="-mt-1 text-sm text-gray-400">{{ '@'.$username }}</div>
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
