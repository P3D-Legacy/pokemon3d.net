<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-800 dark:text-slate-200">
            @lang('Public Skins')
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @component('components.breadcrumb', ['breadcrumbs' => [
                ['url' => route('skin-home'), 'label' => 'Skins'],
                ['label' => __('Public')],
                ['label' => __('Most Popular')],
            ]])
            @endcomponent

            <div class="flex items-center mb-4">
                <a class="border-l border-t border-b text-base font-medium rounded-l-md hover:bg-green-50 px-4 py-2 {{ request()->is('skin/public/new*') ? 'text-green-800 bg-green-100 dark:text-green-300 dark:bg-green-800 dark:border-green-700 dark:hover:bg-green-700' : 'text-slate-800 bg-white dark:bg-slate-700 dark:text-slate-200 dark:border-slate-600 dark:hover:bg-slate-600' }}" href="{{ route('skins-newest') }}">@lang('Newest')</a>
                <a class="border-t border-b border-r text-base font-medium rounded-r-md hover:bg-green-50 px-4 py-2 {{ request()->is('skin/public/popular*') ? 'text-green-800 bg-green-100 dark:text-green-300 dark:bg-green-800 dark:border-green-700 dark:hover:bg-green-700' : 'text-slate-800 bg-white dark:bg-slate-700 dark:text-slate-200 dark:border-slate-600 dark:hover:bg-slate-600' }}" href="{{ route('skins-popular') }}">@lang('Most Popular')</a>
            </div>

            <div class="grid grid-flow-row grid-cols-1 gap-4 auto-rows-max sm:grid-cols-2 lg:grid-cols-3">
                @if(!$skins->count())
                    <p class="text-black dark:text-white">@lang('None found.')</p>
                @endif
                @foreach($skins as $skin)
                    @include('skin.component.card', ['skin' => $skin])
                @endforeach
            </div>

            <div class="mt-4">
                {{ $skins->links() }}
            </div>
        </div>
    </div>

</x-app-layout>
