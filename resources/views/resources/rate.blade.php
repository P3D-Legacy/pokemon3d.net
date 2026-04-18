<x-app-layout>
    <div>
        <div class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
            @component('components.breadcrumb', ['breadcrumbs' => [
                ['url' => route('resource.index'), 'label' => __('Resources')],
                ['url' => route('resource.uuid', $resource->uuid), 'label' => $resource->name],
                ['url' => null, 'label' => __('Leave a Rating')],
            ]])
            @endcomponent

            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-lg shadow-md px-6 py-6 dark:bg-slate-900">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">@lang('Leave a Rating')</h1>
                    </div>

                    <!-- Resource Info -->
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4 mb-6">
                        <h3 class="font-semibold text-slate-900 dark:text-white mb-2">{{ $resource->name }}</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-300">{{ $resource->brief }}</p>
                        <div class="flex items-center mt-2 text-xs text-slate-500 dark:text-slate-400">
                            <span>@lang('By') <span class="font-medium">{{ $resource->user->username }}</span></span>
                            @if($resource->hasReview())
                                <span class="mx-2">•</span>
                                <div class="flex items-center">
                                    <x-review-stars :stars="$resource->averageRating(0)" :size="3" />
                                    <span class="ml-1">{{ $resource->averageRating(1) }} ({{ $resource->numberOfReviews() }} {{ Str::plural('review', $resource->numberOfReviews()) }})</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    @livewire('resource.rating-create', ['resource' => $resource])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
