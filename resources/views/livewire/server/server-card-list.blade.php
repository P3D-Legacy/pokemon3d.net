<div class="flex flex-col">
    <div class="-my-2 sm:-mx-6 lg:-mx-8">
        <p class="py-4 text-center text-gray-300 dark:text-gray-600">{{ __('All server statuses are updated every hour.') }}</p>
        <div class="grid grid-cols-1 gap-4 sm:p-10 sm:grid-cols-2 md:grid-cols-3 auto-rows-auto grid-flow-rows">
            @foreach ($this->servers as $server)
                @livewire('server.server-card', ['server' => $server])
            @endforeach
        </div>
    </div>
</div>