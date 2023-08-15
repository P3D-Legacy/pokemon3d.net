<div>
    <div class="flex flex-col items-center justify-center w-full mx-auto overflow-hidden bg-white rounded-lg shadow-md dark:bg-slate-900 dark:shadow-slate-700">
        <div class="flex flex-col w-full divide-y divide dark:divide-slate-700">
            @foreach ($resources as $resource)
                <a href="{{ route('resource.uuid', $resource->uuid) }}" class="flex hover:bg-green-400/10">
                    <div class="grid items-center w-full grid-rows-2 gap-4 p-3 cursor-pointer select-none md:grid-rows-none md:flex sm:p-4 md:gap-0">
                        <div class="flex-col items-center justify-center hidden w-10 h-10 mr-4 md:flex">
                            <img alt="{{ $resource->user->name }}" src="{{ $resource->user->profile_photo_url ?? asset('img/TreeLogoSmall.png') }}" class="object-cover w-10 h-10 mx-auto rounded-full "/>
                        </div>
                        <div class="flex-1 md:pl-1 md:mr-16">
                            <div class="font-medium dark:text-white">
                                {{ $resource->name }} <span class="text-slate-400 dark:text-slate-500">{{ $resource->updates->first() ? $resource->updates->first()->title : __('Unreleased') }}</span>
                            </div>
                            <div class="text-sm text-slate-400 dark:text-slate-200">
                                {{ $resource->user->username }} &middot; {{ $resource->created_at->diffForHumans() }} &middot; {{ $resource->categories()->first()->name ?? __('Uncategorized') }}
                            </div>
                            <div class="text-xs text-slate-500 truncate dark:text-slate-300">
                                {{ $resource->brief }}
                            </div>
                        </div>
                        <div class="flex flex-col justify-center text-xs text-slate-400 items-left">
                            <div class="flex flex-row justify-between">
                                <span>@lang('Rating'):</span>
                                <span class="flex items-center"><x-review-stars :stars="$resource->averageRating(0)" :size="4" />{{ $resource->hasReview() ? $resource->averageRating(1) : 0 }} ({{ $resource->numberOfReviews() }})</span>
                            </div>
                            <div class="flex flex-row justify-between">
                                <span>@lang('Likes'):</span>
                                <span>{{ $resource->likers()->count() }}</span>
                            </div>
                            <div class="flex flex-row justify-between">
                                <span>@lang('Downloads'):</span>
                                <span>{{ $resource->downloads }}</span>
                            </div>
                            <div class="flex flex-row justify-between">
                                <span>@lang('Updated'):</span>
                                <span>{{ $resource->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    <div class="mt-5 px-2">
        {{ $resources->links() }}
    </div>
</div>
