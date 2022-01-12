<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Categories') }}
            <button onclick="Livewire.emit('openModal', 'create-category')" class="px-2 py-1 ml-4 text-base font-semibold text-center text-white transition duration-200 ease-in bg-green-600 rounded-lg shadow hover:bg-green-700 focus:ring-green-500 focus:ring-offset-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
				</svg>
                Create
            </button>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg dark:bg-gray-900">
                <div class="space-y-6">
                    @livewire('index-category')
                </div>
            </div>
        </div>
    </div>

</x-app-layout>