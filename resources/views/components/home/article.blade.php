<div class="flex flex-col flex-grow flex-shrink w-full p-6 md:w-1/2 xl:w-1/4">
    <div class="flex-1 overflow-hidden bg-white rounded-t rounded-b-none shadow dark:bg-gray-900">
        <a href="#" class="flex flex-wrap no-underline hover:no-underline">
            <p class="w-full px-6 mt-6 text-xs text-gray-600 uppercase dark:text-gray-400 md:text-sm">
                <span class="text-green-700 dark:text-green-200">{{ $post->tags->first()->name }}</span> &mdash; {{ $post->created_at->format('F j, Y') }}
            </p>
            <div class="w-full px-6 mb-3 text-2xl font-bold text-gray-800 truncate dark:text-gray-200">
                @if($post->sticky)
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-6 h-6 mr-1 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd" />
                    </svg>
                @endif
                {{ $post->title }}
            </div>
            <p class="px-6 mb-5 text-sm text-gray-600">
                {!! strip_tags(Str::of(Str::limit($post->body, 150))->markdown()) !!}
            </p>
        </a>
    </div>
    <div class="flex-none px-6 mt-auto overflow-hidden bg-white border-t rounded-t-none rounded-b shadow dark:bg-gray-900 dark:border-black">
        <div class="flex items-center justify-start">
            <a class="px-3 py-2 mx-auto my-3 text-sm font-extrabold bg-green-800 rounded-lg shadow-lg lg:mx-0 text-green-50" href="{{ route('blog.show', $post) }}">
                {{ __('Read more') }}
                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </a>
        </div>
    </div>
</div>