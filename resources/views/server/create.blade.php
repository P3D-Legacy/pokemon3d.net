<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Server: Create') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-4xl py-10 mx-auto sm:px-6 lg:px-8">

            @component('components.breadcrumb', ['breadcrumbs' => [
                ['label' => 'Servers'],
                ['label' => 'Create'],
            ]])
            @endcomponent

            @livewire('server.server-create-form')
        </div>
    </div>
</x-app-layout>