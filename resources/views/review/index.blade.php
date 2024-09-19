<x-app-layout>
    <div>
        <div class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
            @component('components.breadcrumb', ['breadcrumbs' => [
                ['url' => null, 'label' => __('Reviews')],
            ]])
            @endcomponent

            @auth
                <div class="bg-white rounded-lg shadow-md px-6 py-4 mb-6 flex justify-between dark:bg-slate-900">
                    <span class="font-semibold text-slate-900 dark:text-slate-200">{{ __('Want to add a review?') }}</span>
                    <button onclick="Livewire.emit('openModal', 'review.review-form', {{ json_encode([]) }})" class="px-2 py-1 ml-4 text-sm font-bold text-white bg-green-500 rounded hover:bg-green-700"><svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg> @lang('Create')</button>
                </div>
            @endauth

            @livewire('review.review-list')
        </div>
    </div>
</x-app-layout>
