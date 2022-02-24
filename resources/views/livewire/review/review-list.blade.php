<div class="col-span-1 sm:col-span-3 md:col-span-4 rounded-lg overflow-hidden shadow-lg bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 p-4">
    <div class="font-bold text-lg my-1">@lang('Overall Game Reviews')</div>
    <div class="flex items-center"><x-review-stars :stars="$averageRating" /><span class="ml-2">{{ round($averageRating, 2) }} ({{ $numberOfReviews }})</span></div>
</div>

@foreach ($reviews as $review)
    <div class="rounded-lg overflow-hidden shadow-lg bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 p-4">
        <div class="flex items-center h-10 w-full">
            <a href="{{ route('member.show', $review->author) }}">
                <img alt="{{ $review->author->username }}" src="{{ $review->author->profile_photo_url ?? asset('img/TreeLogoSmall.png') }}" class="object-cover w-8 h-8 mx-auto rounded-full inline-flex mr-2"/>
            </a>
            <a href="{{ route('member.show', $review->author) }}" class="text-green-400 hover:text-green-500 mr-2 hover:underline">{{ $review->author->username }}</a>
        </div>
        <div class="">
            <x-review-stars :stars="$review->rating" :size="6" />
            <div class="font-bold text-lg my-1">{{ $review->model->version }}</div>
            <p class="text-gray-700 dark:text-gray-200 text-base">
                {{ $review->review }}
            </p>
        </div>
    </div>
@endforeach