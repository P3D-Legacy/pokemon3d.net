<div class="flex flex-col items-center justify-center w-full mx-auto overflow-hidden bg-white rounded-lg shadow-lg dark:bg-gray-900 dark:shadow-gray-700">
    <div class="w-full px-4 py-5 border-b sm:px-6 dark:border-gray-700">
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
            Resources
        </h3>
    </div>
    <div class="flex flex-col w-full divide-y divide dark:divide-gray-700">
        @foreach ($resources as $resource)
            <a href="{{ route('resource.uuid', $resource->uuid) }}" class="flex hover:bg-green-400/10">
                <div class="grid grid-rows-2 md:grid-rows-none md:flex items-center w-full p-3 sm:p-4 cursor-pointer select-none gap-4 md:gap-0">
                    <div class="hidden md:flex flex-col items-center justify-center w-10 h-10 mr-4">
                        <img alt="{{ $resource->user->name }}" src="{{ $resource->user->profile_photo_url ?? asset('img/TreeLogoSmall.png') }}" class="object-cover w-10 h-10 mx-auto rounded-full "/>
                    </div>
                    <div class="flex-1 md:pl-1 md:mr-16">
                        <div class="font-medium dark:text-white">
                            {{ $resource->name }} <span class="text-gray-400 dark:text-gray-500">{{ $resource->updates->first() ? $resource->updates->first()->title : 'N/A' }}</span>
                        </div>
                        <div class="text-sm text-gray-400 dark:text-gray-200">
                            {{ $resource->user->username }} &middot; {{ $resource->created_at->diffForHumans() }} &middot; {{ $resource->categories()->first()->name }}
                        </div>
                        <div class="text-xs text-gray-500 truncate dark:text-gray-300">
                            {{ $resource->brief }}
                        </div>
                    </div>
                    <div class="flex flex-col justify-center text-xs text-gray-400 items-left">
                        <div class="flex flex-row justify-between">
                            <span>Rating:</span>
                            <span class="flex items-center"><x-review-stars :stars="$resource->averageRating(0)" />{{ $resource->hasReview() ? $resource->averageRating(1) : 0 }} ({{ $resource->numberOfReviews() }})</span>
                        </div>
                        <div class="flex flex-row justify-between">
                            <span>Likes:</span>
                            <span>{{ $resource->likers()->count() }}</span>
                        </div>
                        <div class="flex flex-row justify-between">
                            <span>Donwloads:</span>
                            <span>{{ $resource->downloads }}</span>
                        </div>
                        <div class="flex flex-row justify-between">
                            <span>Updated:</span>
                            <span>{{ $resource->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>