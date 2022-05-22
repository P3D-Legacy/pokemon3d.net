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
                            <x-button xs icon="external-link" info label="{{ trans('Open') }}" wire:click="open('{{ $notification->id }}')" class="mx-1" />
                            @if(!$notification->read_at)
                                <x-button xs icon="eye-off" secondary label="{{ trans('Dismiss') }}" wire:click="dismiss('{{ $notification->id }}')" class="mx-1" />
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
