<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden border border-gray-200 shadow-xl sm:rounded-lg dark:border-gray-900">
                <x-dashboard-block />
            </div>
        </div>
    </div>
</x-app-layout>
