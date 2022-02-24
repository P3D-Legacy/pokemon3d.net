<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            @lang('Servers') <a href="{{ route('server.create') }}" class="px-2 py-1 ml-4 text-sm font-bold text-white bg-green-500 rounded hover:bg-green-700"><svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg> @lang('Create')</a>
        </h2>
    </x-slot>

    <div>
        <div class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">

            @component('components.breadcrumb', ['breadcrumbs' => [
                ['label' => __('Servers')],
            ]])
            @endcomponent

            @livewire('server.server-card-list')
        </div>
    </div>
</x-app-layout>