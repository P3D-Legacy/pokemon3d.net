<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Resource: Create') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-4xl py-10 mx-auto sm:px-6 lg:px-8">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <x-jet-section-title>
                    <x-slot name="title">{{ __('Create a Resource') }}</x-slot>
                    <x-slot name="description">{{ __('*insert some description here*') }}</x-slot>
                </x-jet-section-title>
            
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <form action="{{ route('resource.store') }}" method="POST">
                        @csrf
                        <div class="px-4 py-5 bg-white border border-white shadow dark:bg-gray-800 sm:p-6 dark:border-gray-900">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6">
                                    <x-jet-label for="name" value="{{ __('Name') }}" />
                                    <x-jet-input id="name" type="text" name="name" class="block w-full mt-1" placeholder="My Resource Pack" autofocus value="{{ old('name') ?? '' }}" />
                                    <x-jet-input-error for="name" class="mt-2" />
                                </div>
                    
                                <div class="col-span-6">
                                    <x-jet-label for="description" value="{{ __('Description') }}" />
                                    <textarea id="easyMDE" name="description" name="description">{{ old('description') ?? '' }}</textarea>
                                    <x-jet-input-error for="description" class="mt-2"/>
                                </div>
                            </div>
                        </div>
            
                            <div class="flex items-center justify-end px-4 py-3 text-right shadow bg-gray-50 dark:bg-gray-900 sm:px-6 sm:rounded-bl-md sm:rounded-br-md">
                                <x-jet-button>
                                    {{ __('Save') }}
                                </x-jet-button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>