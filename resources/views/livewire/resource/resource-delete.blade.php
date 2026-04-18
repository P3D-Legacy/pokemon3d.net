<div>
    <div class="bg-red-50 border border-red-200 rounded-lg p-6 dark:bg-red-900/10 dark:border-red-800">
        <div class="flex items-center mb-4">
            <svg class="w-8 h-8 text-red-600 dark:text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-red-900 dark:text-red-100">@lang('Confirm Deletion')</h3>
        </div>
        
        <div class="mb-6">
            <p class="text-red-800 dark:text-red-200 mb-4">
                {{ __('Are you sure you want to delete this :item?', ['item' => strtolower(__('Resource'))]) }}
            </p>
            <div class="bg-red-100 dark:bg-red-900/20 rounded-lg p-4 border border-red-200 dark:border-red-800">
                <p class="font-semibold text-red-900 dark:text-red-100">{{ $resource->name }}</p>
                <p class="text-sm text-red-700 dark:text-red-300 mt-1">{{ $resource->brief }}</p>
            </div>
        </div>
        
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6 dark:bg-yellow-900/10 dark:border-yellow-800">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <div>
                    <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">@lang('Warning')</p>
                    <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                        @lang('This action cannot be undone. All updates, reviews, and associated data will be permanently deleted.')
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end space-x-3 mt-6">
        <a href="{{ route('resource.uuid', $resource->uuid) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-slate-800 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700">
            {{ __('Cancel') }}
        </a>
        <x-danger-button wire:click="delete" wire:loading.attr="disabled">
            <span wire:loading.remove>{{ __('Yes, Delete Resource') }}</span>
            <span wire:loading>{{ __('Deleting...') }}</span>
        </x-danger-button>
    </div>
</div>
