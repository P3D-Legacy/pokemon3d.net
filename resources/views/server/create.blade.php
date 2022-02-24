<x-app-layout>
    <div>
        <div class="max-w-4xl py-10 mx-auto sm:px-6 lg:px-8">

            @component('components.breadcrumb', ['breadcrumbs' => [
                ['label' => __('Servers')],
                ['label' => __('Create')],
            ]])
            @endcomponent

            @livewire('server.server-create-form')
        </div>
    </div>
</x-app-layout>