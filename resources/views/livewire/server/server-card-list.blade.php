<div class="flex flex-col">
    <div class="-my-2 sm:-mx-6 lg:-mx-8">
        <p class="py-4 text-center text-gray-300 dark:text-gray-500">{{ __('All servers are updated every hour.') }}</p>
        <div class="grid grid-cols-3 grid-rows-3 gap-4 grid-flow-rows">
            @foreach ($this->servers as $server)
                @livewire('server.server-card', ['server' => $server])
            @endforeach
        </div>
    </div>
</div>
{{--
<div class="py-3">
    {{ $this->servers->links() }}
</div>
--}}