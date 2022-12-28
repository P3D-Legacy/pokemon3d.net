<x-jet-action-section>
    <x-slot name="title">
        <svg class="inline-block w-auto h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14222 14222">
            <g>
                <path d="M14222 7111c0,-3927 -3184,-7111 -7111,-7111 -3927,0 -7111,3184 -7111,7111 0,3549 2600,6491 6000,7025l0 -4969 -1806 0 0 -2056 1806 0 0 -1567c0,-1782 1062,-2767 2686,-2767 778,0 1592,139 1592,139l0 1750 -897 0c-883,0 -1159,548 -1159,1111l0 1334 1972 0 -315 2056 -1657 0 0 4969c3400,-533 6000,-3475 6000,-7025z" fill="currentColor"/>
                <path d="M9879 9167l315 -2056 -1972 0 0 -1334c0,-562 275,-1111 1159,-1111l897 0 0 -1750c0,0 -814,-139 -1592,-139 -1624,0 -2686,984 -2686,2767l0 1567 -1806 0 0 2056 1806 0 0 4969c362,57 733,86 1111,86 378,0 749,-30 1111,-86l0 -4969 1657 0z" fill="none"/>
            </g>
        </svg>
        Facebook
    </x-slot>

    <x-slot name="description">
        <span class="inline-block">{{ __('Link your account with your :account account.',  ['account' => 'Facebook']) }}</span>
        <span class="inline-block mt-2">{{ __('Last Updated:') }} {{ $updated_at ?? trans('Never') }} &middot; {{ __('Last Verified:') }} {{ $verified_at ?? trans('Never') }}</span>
    </x-slot>

    <x-slot name="content">
        @if (!isset($name))
            <a href="{{ route('facebook.login') }}" class="flex items-center justify-center w-full px-4 py-3 text-sm font-semibold tracking-widest text-white uppercase transition bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 active:bg-blue-400 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25">
                <svg class="inline-block w-auto h-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14222 14222">
                    <g>
                        <path d="M14222 7111c0,-3927 -3184,-7111 -7111,-7111 -3927,0 -7111,3184 -7111,7111 0,3549 2600,6491 6000,7025l0 -4969 -1806 0 0 -2056 1806 0 0 -1567c0,-1782 1062,-2767 2686,-2767 778,0 1592,139 1592,139l0 1750 -897 0c-883,0 -1159,548 -1159,1111l0 1334 1972 0 -315 2056 -1657 0 0 4969c3400,-533 6000,-3475 6000,-7025z" fill="currentColor"/>
                        <path d="M9879 9167l315 -2056 -1972 0 0 -1334c0,-562 275,-1111 1159,-1111l897 0 0 -1750c0,0 -814,-139 -1592,-139 -1624,0 -2686,984 -2686,2767l0 1567 -1806 0 0 2056 1806 0 0 4969c362,57 733,86 1111,86 378,0 749,-30 1111,-86l0 -4969 1657 0z" fill="none"/>
                    </g>
                </svg>
                Connect with Facebook
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
