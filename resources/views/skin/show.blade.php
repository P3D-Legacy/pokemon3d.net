<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{'Public Skins: '.$skin->name}}
        </h2>
    </x-slot>

    
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @component('components.breadcrumb', ['breadcrumbs' => [
                ['label' => 'Skins'],
                ['label' => 'Public'],
                ['label' => $skin->name],
            ]])
            @endcomponent
            
            @include('skin.component.card', ['skin' => $skin])
        </div>
    </div>

</x-app-layout>