<div class="relative ml-3">
    <x-jet-dropdown align="left" width="72">
        <x-slot name="trigger">
            <span class="inline-flex rounded-md">
                <button class="inline-flex items-center p-2 text-sm font-medium leading-4 text-gray-500 transition bg-transparent border border-transparent rounded-full hover:bg-gray-100 hover:text-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    <span class="relative inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if($this->unreadNotifications->count() > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1 py-0.5 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">{{ $this->unreadNotifications->count() }}</span>
                        @endif
                    </span>
                </button>
            </span>
        </x-slot>
        <x-slot name="content">
            @forelse($this->unreadNotifications as $notification)
                <div wire:click="open('{{ $notification->id }}')" class="cursor-pointer">
                    <x-jet-dropdown-link>
                        <div class="flex relative">
                            <div class="flex-shrink-0 inline-flex items-center justify-center text-white relative z-10">
                                @if(isset($notification->data['icon']))
                                    <span class="rounded-full bg-green-500 uppercase p-1 text-xs font-bold mr-2 h-6 w-6">{!! $notification->data['icon'] !!}</span>
                                @endif
                            </div>
                            <div class="flex-grow ml-1">
                                <div class="text-sm leading-5 text-gray-900 dark:text-gray-200">{{ $notification->data['message'] }}</div>
                            </div>
                        </div>
                    </x-jet-dropdown-link>
                </div>
            @empty
                <div class="block px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-300 focus:outline-none focus:bg-gray-100 transition">
                    @lang('No new notifications')
                </div>
            @endforelse
        </x-slot>
    </x-jet-dropdown>
</div>