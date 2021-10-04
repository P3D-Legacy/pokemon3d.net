<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{'Public Skins: '.$skin->name}}
        </h2>
    </x-slot>

    <div class="mt-4">
        @include('game.skin.component.card', ['skin' => $skin])
    </div>

</x-app-layout>