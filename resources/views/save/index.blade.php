<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            @lang('Game Save') {{--<button onclick="Livewire.emit('openModal', 'review.review-form')" class="px-2 py-1 ml-4 text-sm font-bold text-white bg-blue-500 rounded hover:bg-blue-700"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg> @lang('Synchronize')</button> <span class='text-sm text-slate-300 dark:text-slate-600 ml-2'>Last synced: {{ '1901-02-12' }}</span>--}}
        </h2>
    </x-slot>

    <div>
        <div class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
            @component('components.breadcrumb', ['breadcrumbs' => [
                ['url' => null, 'label' => __('Game Save')],
            ]])
            @endcomponent

        </div>
    </div>
</x-app-layout>
