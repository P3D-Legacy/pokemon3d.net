<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Resources') }}
        </h2>
    </x-slot>

    <div>
        @livewire('resource.resource-show', ['resource' => $resource])
    </div>
</x-app-layout>