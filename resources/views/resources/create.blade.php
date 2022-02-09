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
                    <x-slot name="description"></x-slot>
                </x-jet-section-title>
            
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <form action="{{ route('resource.store') }}" method="POST">
                        @csrf
                        <div class="px-4 py-5 overflow-hidden bg-white border border-white shadow dark:bg-gray-800 sm:p-6 dark:border-gray-900 rounded-t-md">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6">
                                    <x-jet-label for="name" value="{{ __('Name') }}" />
                                    <x-jet-input id="name" type="text" name="name" class="block w-full mt-1" placeholder="My Resource Pack" autofocus value="{{ old('name') ?? '' }}" />
                                    <x-jet-input-error for="name" class="mt-2" />
                                </div>

                                <div class="col-span-6">
                                    <x-jet-label for="breif" value="{{ __('Breif') }}" />
                                    <x-jet-input id="breif" type="text" name="breif" class="block w-full mt-1" placeholder="A breif one-line description for My Resource Pack" autofocus value="{{ old('breif') ?? '' }}" />
                                    <x-jet-input-error for="breif" class="mt-2" />
                                </div>
                                <div class="col-span-6">
                                    <x-jet-label for="category" value="{{ __('Category') }}" />
                                    <div class="relative inline-block w-full">
                                        <select class="w-full h-10 pl-3 pr-6 text-base text-gray-800 placeholder-gray-600 border rounded-lg appearance-none focus:shadow-outline" id="category" name="category">
                                            <option value="">Select a category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ (old('category') == $category->id ) ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-800 pointer-events-none">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
                                        </div>
                                    </div>
                                    <x-jet-input-error for="category" class="mt-2" />
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