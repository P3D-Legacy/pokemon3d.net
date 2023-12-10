<div class="relative ml-3">
    <x-jet-dropdown align="right" width="96">
        <x-slot name="trigger">
            <span class="inline-flex rounded-md">
                <button class="inline-flex items-center p-2 text-sm font-medium leading-4 text-slate-500 transition bg-transparent border border-transparent rounded-full hover:bg-slate-100 hover:text-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                    <span class="relative inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if($this->count > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1 py-0.5 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">{{ $this->count }}</span>
                        @endif
                    </span>
                </button>
            </span>
        </x-slot>
        <x-slot name="content">
            <div class="flex px-4 py-2 gap-2 grid grid-flow-col">
                @if($this->count > 0)
                    <div>
                        <button wire:click="dismissAll()" class="focus:outline-none inline-flex justify-center items-center transition-all ease-in duration-100 focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-80 disabled:cursor-not-allowed rounded gap-x-1 text-xs px-2.5 py-1.5 border text-slate-500 hover:bg-slate-100 ring-slate-200 dark:ring-slate-600 dark:border-slate-500 dark:hover:bg-slate-700 dark:ring-offset-slate-800 dark:text-slate-400 w-full">
                            <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                            {{ trans('Dismiss all notifications') }}
                        </button>
                    </div>
                @endif
                <div>
                    <a href="{{ route('notifications.index') }}" class="focus:outline-none inline-flex justify-center items-center transition-all ease-in duration-100 focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-80 disabled:cursor-not-allowed rounded gap-x-1 text-xs px-2.5 py-1.5 border text-slate-500 hover:bg-slate-100 ring-slate-200 dark:ring-slate-600 dark:border-slate-500 dark:hover:bg-slate-700 dark:ring-offset-slate-800 dark:text-slate-400 w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        {{ trans('Show all notifications') }}
                    </a>
                </div>
            </div>
            @forelse($this->unreadNotifications as $notification)
                <div class="block px-4 py-2 text-sm leading-5 text-slate-700 dark:text-slate-300 transition border-slate-200 dark:border-slate-900 border-b">
                    <div class="flex relative">
                        <div class="flex-shrink-0 inline-flex items-center justify-center text-white relative z-10">
                            @if(isset($notification->data['icon']))
                                <span class="rounded-full bg-green-500 uppercase p-1 text-xs font-bold mr-2 h-6 w-6">{!! $notification->data['icon'] !!}</span>
                            @endif
                        </div>
                        <div class="flex-grow ml-3">
                            <p class="text-sm leading-5 font-medium text-slate-900 dark:text-slate-200">
                                {!! $notification->data['message'] !!}
                            </p>
                            <div class="text-sm leading-5 text-slate-500 dark:text-slate-400">
                                {{ $notification->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <div class="flex ml-1 items-end">
                            <button wire:click="open('{{ $notification->id }}')" class="outline-none inline-flex justify-center items-center transition-all ease-in duration-150 focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-80 disabled:cursor-not-allowed rounded-full w-5 h-5 ring-blue-500 text-white bg-blue-500 hover:bg-blue-600 hover:ring-blue-600 dark:ring-offset-slate-800 dark:bg-blue-700 dark:ring-blue-700 dark:hover:bg-blue-600 dark:hover:ring-blue-600 m-0.5">
                                <svg class="w-2 h-2 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </button>
                            <button wire:click="dismiss('{{ $notification->id }}')" class="outline-none inline-flex justify-center items-center transition-all ease-in duration-150 focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-80 disabled:cursor-not-allowed rounded-full w-5 h-5 ring-slate-500 text-white bg-slate-500 hover:bg-slate-600 hover:ring-slate-600 dark:ring-offset-slate-800 dark:bg-slate-700 dark:ring-slate-700 dark:hover:bg-slate-600 dark:hover:ring-slate-600 m-0.5">
                                <svg class="w-2 h-2 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="block px-4 py-2 text-sm leading-5 text-slate-400 dark:text-slate-500 focus:outline-none focus:bg-slate-100 transition text-center">
                    @lang('No new notifications')
                </div>
            @endforelse
        </x-slot>
    </x-jet-dropdown>
</div>
