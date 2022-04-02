<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            @lang('Uploaded Skins')
        </h2>
    </x-slot>

    <h2 class="pb-1 mt-4 mb-4 text-3xl font-extrabold leading-9 border-b-2 border-gray-100 text-gray-50">@lang('Uploaded Skins')</h2>

    <div class="grid grid-flow-row grid-cols-1 gap-4 auto-rows-max sm:grid-cols-2 lg:grid-cols-3">
        @if(!$skins->count())
            <p class="text-white">@lang('None found.')</p>
        @endif
        @foreach($skins as $skin)
            @include('skin.component.card', ['skin' => $skin])
        @endforeach
    </div>

</x-app-layout>