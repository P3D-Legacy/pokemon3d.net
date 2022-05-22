<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            @lang('Notifications')
        </h2>
    </x-slot>
    <div class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-center my-4">
            <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-900 text-gray-900 dark:text-gray-200 overflow-hidden shadow w-full">
                @forelse($notifications as $notification)
                    <div class="flex items-center px-4 py-2 border-b dark:border-gray-700 {{ $notification->read_at ? 'bg-gray-100 dark:bg-gray-800/60' : '' }}">
                        <div class="flex-shrink-0 inline-flex items-center justify-center text-white relative z-10">
                            @if(isset($notification->data['icon']))
                                <span class="rounded-full bg-green-500 uppercase p-1.5 text-xs font-bold mr-2 h-8 w-8">{!! $notification->data['icon'] !!}</span>
                            @endif
                        </div>
                        <div class="flex-grow ml-2">
                            <p class="leading-5 font-medium text-gray-900 dark:text-gray-200">
                                {!! $notification->data['message'] !!}
                            </p>
                            <div class="text-xs leading-5 text-gray-500 dark:text-gray-400">
                                {{ $notification->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <div class="flex ml-5 items-end">
                            <button wire:click="open('{{ $notification->id }}')" class="focus:outline-none inline-flex justify-center items-center transition-all ease-in duration-100 focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-80 disabled:cursor-not-allowed rounded gap-x-2 text-sm leading-4 px-3 py-2 ring-blue-500 text-white bg-blue-500 hover:bg-blue-600 hover:ring-blue-600 dark:ring-offset-slate-800 dark:bg-blue-700 dark:ring-blue-700 dark:hover:bg-blue-600 dark:hover:ring-blue-600 mx-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                {{ trans('Open') }}
                            </button>
                            @if(!$notification->read_at)
                                <button wire:click="dismiss('{{ $notification->id }}')" class="focus:outline-none inline-flex justify-center items-center transition-all ease-in duration-100 focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-80 disabled:cursor-not-allowed rounded gap-x-2 text-sm leading-4 px-3 py-2 ring-slate-500 text-white bg-slate-500 hover:bg-slate-600 hover:ring-slate-600 dark:ring-offset-slate-800 dark:bg-slate-700 dark:ring-slate-700 dark:hover:bg-slate-600 dark:hover:ring-slate-600 mx-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                    {{ trans('Dismiss') }}
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center">
                        <p class="text-gray-500">
                            @lang('No notifications')
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
        {{ $notifications->links('vendor.pagination.tailwind') }}
    </div>
</div>
