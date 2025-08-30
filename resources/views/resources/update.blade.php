<x-app-layout>
    <div>
        <div class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
            @component('components.breadcrumb', ['breadcrumbs' => [
                ['url' => route('resource.index'), 'label' => __('Resources')],
                ['url' => route('resource.uuid', $resource->uuid), 'label' => $resource->name],
                ['url' => null, 'label' => __('Post Update')],
            ]])
            @endcomponent

            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-lg shadow-md px-6 py-6 dark:bg-slate-900">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">@lang('Post an Update')</h1>
                        <a href="{{ route('resource.uuid', $resource->uuid) }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                    </div>

                    @livewire('resource.update-create', ['resource_uuid' => $resource->uuid])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
