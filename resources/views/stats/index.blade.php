<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Stats') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white dark:bg-gray-900 shadow-xl sm:rounded-lg">
                @livewire('admin.user-registration-stats-graph')
                @livewire('admin.resource-creation-stats-graph')
            </div>
        </div>
    </div>

</x-app-layout>
