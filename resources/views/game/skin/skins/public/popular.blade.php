<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{'Public Skins'}}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center mb-4">
                <a class="border-l border-t border-b text-base font-medium rounded-l-md hover:bg-gray-100 px-4 py-2 {{ request()->is('game/skin/public/new*') ? 'text-green-800 bg-green-50' : 'text-gray-800' }} bg-white" href="{{ route('skins-newest') }}">Newest</a>
                <a class="border-t border-b border-r text-base font-medium rounded-r-md hover:bg-gray-100 px-4 py-2 {{ request()->is('game/skin/public/popular*') ? 'text-green-800 bg-green-50' : 'text-gray-800 bg-white' }}" href="{{ route('skins-popular') }}">Most Popular</a>
            </div>

            <div class="grid grid-flow-row grid-cols-1 gap-4 auto-rows-max sm:grid-cols-2 lg:grid-cols-3">
                @if(!$skins->count())
                    <p class="text-white">None found.</p>
                @endif
                @foreach($skins as $skin)
                    @include('game.skin.component.card', ['skin' => $skin])
                @endforeach
            </div>

            <div class="mt-4">
                {{ $skins->links() }}
            </div>
        </div>
    </div>

</x-app-layout>