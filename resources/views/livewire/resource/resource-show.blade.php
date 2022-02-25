<div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
    
    @component('components.breadcrumb', ['breadcrumbs' => [
        ['url' => route('resource.index'), 'label' => __('Resources')],
        ['url' => route('resource.category', Str::lower($resource->categories()->first()->name)), 'label' => $resource->categories()->first()->name],
        ['url' => null, 'label' => $resource->name],
    ]])
    @endcomponent

    <div class="grid grid-rows-2 gap-4 px-2 mb-4 sm:grid-flow-col sm:grid-rows-none sm:grid-cols-3 sm:px-0">
        <div class="text-2xl sm:col-span-2 dark:text-white">
            {{ $resource->name }} <span class="text-gray-400 dark:text-gray-500">{{ $resource->updates->first() ? $resource->updates->first()->title : 'Unreleased' }}</span>
        </div>
        <div class="flex justify-end gap-1">
            @if(auth()->user()->id != $resource->user_id || config('app.debug') == true)
                @livewire('resource.resource-like', ['resource' => $resource])
                <button onclick="Livewire.emit('openModal', 'resource.rating-create', {{ json_encode(['resource' => $resource->id]) }})" class="px-2 py-1 transition-colors duration-150 rounded-md text-sky-100 bg-sky-600 focus:shadow-outline hover:bg-sky-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    @lang('Leave a rating')
                </button>
            @endif
            @if($resource->updates->first())
                <button wire:click="download" class="px-2 py-1 text-green-100 transition-colors duration-150 bg-green-600 rounded-md focus:shadow-outline hover:bg-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    @lang('Download')
                </button>
            @endif
            @if(auth()->user()->id == $resource->user_id)
                <div x-data="{ dropdownOpen: false }" class="" x-cloak>
                    <button @click="dropdownOpen = !dropdownOpen" class="px-2 py-1 text-gray-100 transition-colors duration-150 bg-gray-600 rounded-md focus:shadow-outline hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                        </svg>
                    </button>
                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute z-10 w-48 py-2 mt-2 bg-white border border-gray-200 rounded-md shadow-md dark:bg-gray-900 dark:border-gray-800">
                        <button onclick="Livewire.emit('openModal', 'resource.resource-form', {{ json_encode(['resource' => $resource->id]) }})" class="block w-full px-4 py-2 text-sm text-left text-gray-700 dark:text-gray-300 hover:bg-green-700 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            @lang('Edit')
                        </button>
                        <button onclick="Livewire.emit('openModal', 'resource.update-create', {{ json_encode(['resource_uuid' => $resource->uuid]) }})" class="block w-full px-4 py-2 text-sm text-left text-gray-700 dark:text-gray-300 hover:bg-green-700 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            @lang('Post a update')
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="w-full p-4 bg-white rounded-lg shadow-lg dark:bg-gray-900 dark:shadow-gray-700">
        <div class="grid grid-rows-2 gap-4 sm:grid-rows-none sm:grid-cols-4">
            <div class="sm:col-span-3">
                <div class="mb-4 text-xs text-gray-400">{{ $resource->brief }}</div>
                <div class="prose dark:prose-invert">
                    {!! Str::of($resource->description)->markdown() !!}
                </div>
            </div>
            <div class="flex text-xs text-gray-500 sm:flex-col sm:justify-center dark:text-gray-300 items-left">
                <div class="p-4 bg-gray-100 rounded dark:bg-gray-800">
                    <div class="flex flex-row justify-between">
                        <span>@lang('Author'):</span>
                        <span><a href="{{ route('member.show', $resource->user) }}" class="text-green-400 hover:text-green-500 hover:underline">{{ $resource->user->username }}</a></span>
                    </div>
                    <div class="flex flex-row justify-between">
                        <span>@lang('Rating'):</span>
                        <span class="flex items-center"><x-review-stars :stars="$resource->averageRating(0)" :size="4" />{{ $resource->hasReview() ? $resource->averageRating(1) : 0 }} ({{ $resource->numberOfReviews() }})</span>
                    </div>
                    <div class="flex flex-row justify-between">
                        <span>@lang('Donwloads'):</span>
                        <span>{{ $resource->downloads }}</span>
                    </div>
                    <div class="flex flex-row justify-between">
                        <span>@lang('Views'):</span>
                        <span>{{ App\Helpers\NumberHelper::nearestK(views($resource)->count()) }}</span>
                    </div>
                    <div class="flex flex-row justify-between">
                        <span>@lang('Created'):</span>
                        <span>{{ $resource->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex flex-row justify-between">
                        <span>@lang('Updated'):</span>
                        <span>{{ $resource->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex flex-col items-center justify-center w-full mx-auto mt-10 overflow-hidden bg-white rounded-lg shadow-lg dark:bg-gray-900 dark:shadow-gray-700">
        <div class="w-full px-4 py-5 border-b sm:px-6 dark:border-gray-700">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                @lang('Updates')
            </h3>
        </div>
        <div class="flex flex-col w-full divide-y divide dark:divide-gray-700">
            @forelse ($resource->updates as $update)
                <div class="flex items-center justify-between w-full gap-6 p-4 dark:text-white">
                    <div class="flex">
                        <a href="#" class="text-green-400 hover:underline">{{ $update->title }}</a>
                    </div>
                    <div class="flex-1 text-gray-400">
                        {!! strip_tags(Str::of(Str::limit($update->description, 300))->markdown()) !!}
                    </div>
                    <div class="flex text-sm">
                        {{ $update->game_version->version }}
                    </div>
                    <div class="flex text-sm">
                        {{ $update->created_at->diffForHumans() }}
                    </div>
                    <div class="flex text-sm">
                        {{ $update->downloads }} @lang('downloads')
                    </div>
                    {{--
                    <div class="flex">
                        <button class="px-2 py-1 text-sm text-green-100 transition-colors duration-150 bg-green-600 rounded-md focus:shadow-outline hover:bg-green-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            @lang('Download')
                        </button>
                    </div>
                    --}}
                </div>
            @empty
                <div class="flex items-center justify-center w-full p-4">
                    <div class="text-center dark:text-gray-400">
                        {{ __('No updates found.') }}
                    </div>
                </div>
            @endforelse
        </div>
    </div>
    <div class="flex flex-col items-center justify-center w-full mx-auto mt-10 overflow-hidden bg-white rounded-lg shadow-lg dark:bg-gray-900 dark:shadow-gray-700">
        <div class="w-full px-4 py-5 border-b sm:px-6 dark:border-gray-700">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                @lang('Latest reviews')
            </h3>
        </div>
        <div class="flex flex-col w-full divide-y divide dark:divide-gray-700">
            @forelse ($resource->reviews as $review)
                <div class="flex items-center w-full p-4">
                    <div class="flex flex-col items-center justify-center w-10 h-10 mr-4">
                        <a href="{{ route('member.show', $review->author) }}"><img alt="{{ $review->author->username }}" src="{{ $review->author->profile_photo_url ?? asset('img/TreeLogoSmall.png') }}" class="object-cover w-10 h-10 mx-auto rounded-full "/></a>
                    </div>
                    <div class="flex-1 pl-1 mr-16">
                        <div class="flex items-center text-sm text-gray-400 dark:text-gray-200">
                            <a href="{{ route('member.show', $review->author) }}" class="mr-2 text-green-400 hover:text-green-500 hover:underline">{{ $review->author->username }}</a> &middot; <x-review-stars :stars="$review->rating" :size="4" /> &middot; {{ $review->created_at->diffForHumans() }}
                        </div>
                        <div class="text-xs text-gray-500 truncate dark:text-gray-300">
                            
                        </div>
                        <div class="py-1 font-medium dark:text-white">
                            {{ $review->review }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="flex items-center justify-center w-full p-4">
                    <div class="text-center dark:text-gray-400">
                        {{ __('Nothing found.') }}
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>