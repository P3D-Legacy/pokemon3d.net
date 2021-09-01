<div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center" id="gamejolt-modal">
    <div class="relative w-full my-6 mx-auto max-w-2xl">
        <!--content-->
        <div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white dark:bg-gray-800 outline-none focus:outline-none">
            <!--header-->
            <div class="flex items-start justify-between rounded-t">
                <button class="p-3 ml-auto bg-transparent border-0 text-black dark:text-white opacity-50 float-right text-3xl leading-none font-semibold outline-none focus:outline-none" onclick="toggleModal('gamejolt-modal')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 fill-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <!--body-->
            <div class="relative p-6 pt-0 flex-auto">
                <x-jet-form-section submit="save">
                    <x-slot name="title">
                        <img src="{{ asset('img/gamejolt-logo-light-1x.png') }}" class="inline-block">
                    </x-slot>

                    <x-slot name="description">
                        <span class="inline-block mt-4 font-semibold dark:text-gray-100">
                            <a href="https://gamejolt.com/help/tokens" target="_blank" class="hover:text-green-700 dark:hover:text-green-300 hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                </svg>{{ __('What\'s my token?') }}
                            </a>
                        </span>
                    </x-slot>

                    <x-slot name="form">
                        <div class="col-span-6">
                            <x-jet-label for="username" value="{{ __('Username') }}" />
                            <x-jet-input id="username" type="text" class="mt-1 block w-full" wire:model="username" />
                            <x-jet-input-error for="username" class="mt-2" />
                        </div>

                        <div class="col-span-6">
                            <x-jet-label for="token" value="{{ __('Token') }}" />
                            <x-jet-input id="token" type="password" class="mt-1 block w-full" wire:model="token" />
                            <x-jet-input-error for="token" class="mt-2" />
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
            </div>
        </div>
    </div>
</div>
