<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Resource:') }} {{ $resource->name }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-4xl py-10 mx-auto sm:px-6 lg:px-8">
            {{ Str::of($resource->description)->markdown() }}
        </div>
    </div>
</x-app-layout>