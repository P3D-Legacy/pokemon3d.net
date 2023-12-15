<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-800 dark:text-slate-200">
            {{ __('Create Skin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @component('components.breadcrumb', ['breadcrumbs' => [
                ['label' => __('Skins')],
                ['label' => __('Create')],
            ]])
            @endcomponent

            <div class="w-full m-auto overflow-hidden rounded-lg shadow-md h-90">
                <div class="block w-full h-full">
                    <div class="w-full p-4 bg-white dark:bg-slate-900">
                        <form role="form" action="{{ route('skin-store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <x-label for="name" value="{{ __('Name') }}" />
                                <x-input id="name" name="name" type="text" class="block w-full mt-1" value="{{ old('name') ? old('name') : '' }}" autocomplete="name" />
                                <x-input-error for="name" class="mt-2" />
                            </div>
                            <div class="mb-3">
                                <x-label for="image" value="{{ __('Select image file') }}" />
                                <input class="block w-full mt-1 text-black border-slate-300 rounded-md shadow-sm dark:text-white focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 file:mr-4 file:py-1 file:px-2 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-green-500 file:text-green-50 hover:file:bg-green-800" type="file" id="formFile" name="image">
                                <x-input-error for="image" class="mt-2" />
                            </div>
                            <div class="mt-6 mb-3">
                                <label for="public" class="flex items-center">
                                    <x-checkbox id="public" name="public" />
                                    <span class="ml-2 text-sm text-slate-600 dark:text-slate-300">@lang('Public') <span class="ml-2 text-sm text-slate-500">@lang('Other users will be able to see this skin')</span></span>
                                </label>
                                <x-input-error for="public" class="mt-2" />
                            </div>
                            <div class="mb-3">
                                <label for="rules" class="flex items-center">
                                    <x-checkbox id="rules" name="rules" />
                                    <span class="ml-2 text-sm text-slate-600 dark:text-slate-300"><strong>I accept and understand the rules</strong> for uploading a custom skin. <span class="my-4 text-sm text-slate-500">Read the rules on the <a href="{{ route('skin-home') }}" class="text-green-500">skin home page</a>.</span></span>
                                </label>
                                <x-input-error for="rules" class="mt-2" />
                            </div>
                            <x-button>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                @lang('Upload')
                            </x-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>
</x-app-layout>
