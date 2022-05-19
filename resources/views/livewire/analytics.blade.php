<div>
    <x-slot name="header">
        {{ __('Domain') }} &mdash; {{ $domain }}
        <div class="block mr-auto text-xs md:text-sm font-bold text-gray-500 dark:text-gray-300">
            <svg class="inline w-2 mr-1 text-green-500 fill-current" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"><circle cx="8" cy="8" r="8"></circle></svg>
            {{ $realtimeVisitors }} <span class="hidden sm:inline-block">@lang('current visitors')</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="sm:hidden">
                <label for="tabs" class="sr-only">Select period</label>
                <select id="tabs" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    @foreach($periods as $period)
                        <option value="{{ $period }}" wire:click="changePeriod('{{ $period }}')">{{ $period }}</option>
                    @endforeach
                </select>
            </div>
            <ul class="hidden mb-4 text-sm font-medium text-center text-gray-500 rounded-lg divide-x divide-gray-200 shadow sm:flex dark:divide-gray-700 dark:text-gray-400 overflow-hidden">
                @foreach($periods as $period)
                    <li class="w-full" wire:click="changePeriod('{{ $period }}')">
                        <span class="inline-block p-4 w-full cursor-pointer {{ ($period === $selectedPeriod ? 'text-gray-900 bg-gray-100 focus:ring-4 focus:ring-blue-300 active focus:outline-none dark:bg-gray-700 dark:text-white' : 'bg-white hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:hover:text-white dark:bg-gray-900 dark:hover:bg-gray-700') }}" aria-current="page">{{ $period }}</span>
                    </li>
                @endforeach
            </ul>


            <div class="sm:grid sm:h-32 sm:grid-flow-row sm:gap-4 sm:grid-cols-4">
                <div class="flex flex-col justify-center px-4 py-4 bg-white dark:bg-black dark:border-gray-700 border border-gray-300 rounded-lg sm:mt-0">
                    <div>
                        <p class="text-3xl font-semibold text-center text-gray-800 dark:text-gray-100">{{ $visitors }}</p>
                        <p class="text-lg text-center text-gray-500">@lang('Visitors')</p>
                    </div>
                </div>

                <div class="flex flex-col justify-center px-4 py-4 mt-4 bg-white dark:bg-black dark:border-gray-700 border border-gray-300 rounded-lg sm:mt-0">
                    <div>
                        <p class="text-3xl font-semibold text-center text-gray-800 dark:text-gray-100">{{ $pageviews }}</p>
                        <p class="text-lg text-center text-gray-500">@lang('Pageviews')</p>
                    </div>
                </div>

                <div class="flex flex-col justify-center px-4 py-4 mt-4 bg-white dark:bg-black dark:border-gray-700 border border-gray-300 rounded-lg sm:mt-0">
                    <div>
                        <p class="text-3xl font-semibold text-center text-gray-800 dark:text-gray-100">{{ $bounceRate }}%</p>
                        <p class="text-lg text-center text-gray-500">@lang('Bounce Rate')</p>
                    </div>
                </div>

                <div class="flex flex-col justify-center px-4 py-4 mt-4 bg-white dark:bg-black dark:border-gray-700 border border-gray-300 rounded-lg sm:mt-0">
                    <div>
                        <p class="text-3xl font-semibold text-center text-gray-800 dark:text-gray-100">{{ $visitDuration }}s</p>
                        <p class="text-lg text-center text-gray-500">@lang('Visit Duration')</p>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>

