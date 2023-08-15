<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-800 dark:text-slate-200">
            {{ __('Public Skins') . ': ' . $skin->name }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @component('components.breadcrumb', ['breadcrumbs' => [
                ['label' => __('Skins')],
                ['label' => __('Public')],
                ['label' => $skin->name],
            ]])
            @endcomponent

            @include('skin.component.card', ['skin' => $skin])
        </div>
    </div>

</x-app-layout>
