<div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center" id="xenforo-modal">
    <div class="relative w-full my-6 mx-auto max-w-2xl">
        <!--content-->
        <div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white dark:bg-gray-800 outline-none focus:outline-none">
            <!--header-->
            <div class="flex items-start justify-between rounded-t">
                <button class="p-3 ml-auto bg-transparent border-0 text-black dark:text-white opacity-50 float-right text-3xl leading-none font-semibold outline-none focus:outline-none" onclick="toggleModal('xenforo-modal')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 fill-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <!--body-->
            <div class="relative p-6 pt-0 flex-auto">
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
            </div>
        </div>
    </div>
</div>
