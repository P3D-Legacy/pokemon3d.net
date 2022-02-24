<div class="col-span-1 p-4 overflow-hidden text-gray-700 border border-gray-200 rounded-lg shadow-lg sm:col-span-2 md:col-span-3 lg:col-span-4 bg-gray-50 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
    <div class="my-1 text-lg font-bold">@lang('Overall Game Reviews')</div>
    <div class="flex items-center"><x-review-stars :stars="$averageRating" /><span class="ml-2">{{ round($averageRating, 2) }} ({{ $numberOfReviews }})</span></div>
</div>

@foreach ($reviews as $review)
    <div class="p-4 overflow-hidden border border-gray-200 rounded-lg shadow-lg bg-gray-50 dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700">
        <div class="flex items-center w-full h-10">
            <a href="{{ route('member.show', $review->author) }}">
                <img alt="{{ $review->author->username }}" src="{{ $review->author->profile_photo_url ?? asset('img/TreeLogoSmall.png') }}" class="inline-flex object-cover w-8 h-8 mx-auto mr-2 rounded-full"/>
            </a>
            <a href="{{ route('member.show', $review->author) }}" class="mr-2 text-green-400 hover:text-green-500 hover:underline">{{ $review->author->username }}</a>
        </div>
        <div>
            <x-review-stars :stars="$review->rating" :size="6" />
            <div class="my-1 text-lg font-bold">{{ $review->model->version }}</div>
            <p class="text-gray-700 break-all dark:text-gray-200">
                {{ $review->review }}
            </p>
        </div>
    </div>
@endforeach