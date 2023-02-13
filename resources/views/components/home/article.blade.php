<div class="flex flex-col flex-grow w-full p-2 shrink md:w-1/2 xl:w-1/4">
    <div class="flex-1 overflow-hidden bg-white rounded-t rounded-b-none shadow dark:bg-gray-900">
        <a href="{{ route('blog.show', $post->uuid) }}" class="flex flex-wrap no-underline hover:no-underline">
            <p class="w-full px-4 mt-4 text-xs text-gray-600 uppercase dark:text-gray-400 md:text-sm">
                @if($post->sticky)
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 mr-1 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd" />
                    </svg>
                @endif
                <span class="text-green-800 dark:text-green-200">{{ $post->tags->first()->name }}</span> &mdash; {{ $post->published_at->isoFormat('LL') }}
            </p>
            <div class="w-full px-4 mb-2 text-xl font-bold text-gray-800 sm:truncate dark:text-gray-200">
                {{ $post->title }}
            </div>
            <p class="hidden px-4 mb-4 text-sm text-gray-700 dark:text-gray-300 sm:block">
                {!! strip_tags(Str::of(Str::limit($post->body, 150))->markdown()) !!}
            </p>
        </a>
    </div>
    <div class="flex-none p-3 mt-auto overflow-hidden bg-white border-t rounded-t-none rounded-b shadow dark:bg-gray-900 dark:border-black">
        <div class="flex items-center justify-start">
            <a class="px-3 py-2 mx-auto text-sm font-extrabold bg-green-800 rounded-lg shadow-lg lg:mx-0 text-green-50" href="{{ route('blog.show', $post->uuid) }}">
                {{ __('Read more') }}
                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </a>
        </div>
    </div>
</div>
