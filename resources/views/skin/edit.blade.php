<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-800 dark:text-slate-200">
            {{ __('Edit') . ': ' . $skin->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @component('components.breadcrumb', ['breadcrumbs' => [
                ['label' => __('Skins')],
                ['label' => __('Edit')],
                ['label' => $skin->name],
            ]])
            @endcomponent

            <div class="w-full m-auto overflow-hidden rounded-lg shadow-md h-90">
                <div class="block w-full h-full">
                    <div class="w-full p-4 bg-white dark:bg-slate-900">
                        <form role="form" action="{{ route('skin-update', $skin->uuid) }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <x-label for="name" value="{{ __('Name') }}" />
                                <x-input id="name" name="name" type="text" class="block w-full mt-1" value="{{ old('name') ? old('name') : $skin->name }}" autocomplete="name" />
                                <x-input-error for="name" class="mt-2" />
                            </div>
                            <div class="mt-6 mb-3">
                                <input class="text-green-600 border-slate-300 rounded shadow-sm focus:border-green-300 focus:ring focus:ring-offset-0 focus:ring-green-200 focus:ring-opacity-50" type="checkbox" id="checkPublic" name="public" @if (old('public') ?? $skin->public) {{'checked'}} @endif>
                                <label class="text-slate-700 dark:text-slate-300" for="checkPublic">@lang('Public') <span class="ml-2 text-sm text-slate-500">@lang('Other users will be able to see this skin')</span></label>
                                <x-input-error for="public" class="mt-2" />
                            </div>
                            <x-button>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                @lang('Save')
                            </x-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
