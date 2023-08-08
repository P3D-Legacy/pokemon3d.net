<div class="flex flex-col">
    <p class="pt-4 pb-1 text-center text-gray-300 dark:text-gray-600">{{ __('All server statuses are updated every hour.') }}</p>
    @if($this->my_servers->count() > 0)
        <h1 class="text-xl text-gray-600 dark:text-gray-300">{{ __('Your servers') }}:</h1>
        <div class="grid grid-cols-1 gap-4 sm:p-5 sm:grid-cols-2 md:grid-cols-3 auto-rows-auto grid-flow-rows">
            @foreach($this->my_servers as $server)
                @livewire('server.server-card', ['server' => $server])
            @endforeach
        </div>
        <div class="border-t my-8 border-dotted border-gray-300 dark:border-gray-600"></div>
    @endif
    <div class="grid grid-cols-1 gap-4 sm:p-5 sm:grid-cols-2 md:grid-cols-3 auto-rows-auto grid-flow-rows">
        @foreach($this->servers as $server)
            @livewire('server.server-card', ['server' => $server])
        @endforeach
    </div>
</div>
