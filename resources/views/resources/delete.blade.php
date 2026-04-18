<x-app-layout>
    <div>
        <div class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
            @component('components.breadcrumb', ['breadcrumbs' => [
                ['url' => route('resource.index'), 'label' => __('Resources')],
                ['url' => route('resource.uuid', $resource->uuid), 'label' => $resource->name],
                ['url' => null, 'label' => __('Delete')],
            ]])
            @endcomponent

            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-lg shadow-md px-6 py-6 dark:bg-slate-900">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">@lang('Delete Resource')</h1>
                    </div>

                    @livewire('resource.resource-delete', ['resource' => $resource])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
