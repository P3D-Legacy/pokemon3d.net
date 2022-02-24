<x-app-layout>
    <div>
        <div class="max-w-4xl py-10 mx-auto sm:px-6 lg:px-8">

            @component('components.breadcrumb', ['breadcrumbs' => [
                ['label' => 'Servers'],
                ['label' => 'Edit'],
                ['label' => $server->name],
            ]])
            @endcomponent

            @livewire('server.server-edit-form', ['server' => $server])
        </div>
    </div>
</x-app-layout>