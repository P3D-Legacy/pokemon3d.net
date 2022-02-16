<div class="flex flex-col items-center justify-center w-full mx-auto mt-10 overflow-hidden bg-white rounded-lg shadow-lg dark:bg-gray-900 dark:shadow-gray-700">
    <div class="flex flex-col w-full divide-y divide dark:divide-gray-700">
        @foreach ($categories as $category)
            <div class="flex items-center justify-between w-full p-4 dark:text-white">
                <div class="flex-1">
                    {{ $category->name }}
                </div>
                <div class="flex gap-2">
                    <button onclick="Livewire.emit('openModal', 'category.category-form', {{ json_encode(['category' => $category->id]) }})" class="px-2 py-1 text-sm text-yellow-100 transition-colors duration-150 bg-yellow-600 rounded-md focus:shadow-outline hover:bg-yellow-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                          </svg>
                        {{ __('Edit') }}
                    </button>
                    <button onclick="Livewire.emit('openModal', 'category.category-delete', {{ json_encode(['category_id' => $category->id]) }})" class="px-2 py-1 text-sm text-red-100 transition-colors duration-150 bg-red-600 rounded-md focus:shadow-outline hover:bg-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        {{ __('Delete') }}
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>