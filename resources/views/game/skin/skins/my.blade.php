<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            My Skins &mdash; {{ Auth::user()->gamejolt->skins()->count() }} / {{ env('SKIN_MAX_UPLOAD') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-flow-row grid-cols-1 gap-4 auto-rows-max sm:grid-cols-2 lg:grid-cols-3">
                @foreach($skins as $skin)
                    @include('game.skin.component.card', ['skin' => $skin])
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>