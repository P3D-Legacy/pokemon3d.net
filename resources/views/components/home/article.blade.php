<a href="{{ route('blog.show', $post->uuid) }}" class="relative block overflow-hidden rounded-lg border border-slate-200 p-4 mx-4 sm:mx-0 sm:p-6 lg:p-6 bg-white hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:hover:bg-slate-800 justify-between shadow-md">
    <span class="absolute inset-x-0 bottom-0 h-2 {{ $post->sticky ? 'bg-red-500' : 'bg-green-600' }}"></span>

    <div class="sm:flex sm:justify-between sm:gap-4">
        <div>
            <h3 class="text-lg font-bold text-slate-900 sm:text-xl dark:text-slate-100">
                @if($post->sticky)
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 mr-1 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z"
                              clip-rule="evenodd" />
                    </svg>
                @endif
                {{ $post->title }}
            </h3>

            <p class="mt-1 text-xs font-medium text-slate-600">
                <span class="inline-flex items-center justify-center rounded-full bg-green-100 px-2 py-0.5 text-green-700 dark:bg-green-700/80 dark:text-green-100">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 inline-block mr-1">
                        <path fill-rule="evenodd" d="M5.25 2.25a3 3 0 00-3 3v4.318a3 3 0 00.879 2.121l9.58 9.581c.92.92 2.39 1.186 3.548.428a18.849 18.849 0 005.441-5.44c.758-1.16.492-2.629-.428-3.548l-9.58-9.581a3 3 0 00-2.122-.879H5.25zM6.375 7.5a1.125 1.125 0 100-2.25 1.125 1.125 0 000 2.25z" clip-rule="evenodd" />
                    </svg>
                    <span class="whitespace-nowrap text-xs">{{ $post->tags->first()->name }}</span>
                </span>
            </p>
        </div>

        <div class="hidden sm:block sm:shrink-0">
            <img
                alt="{{ $post->user->username }}"
                src="{{ $post->user->profile_photo_url }}"
                class="h-12 w-12 rounded-md object-cover shadow-sm"
            />
        </div>
    </div>

    <div class="mt-4 mb-14">
        <p class="max-w-[40ch] text-sm text-slate-600 dark:text-slate-200">
            {{ $post->excerpt }}
        </p>
    </div>

    <dl class="mt-6 flex gap-4 xl:gap-6 bottom-0 absolute pb-6">
        <div class="flex flex-col-reverse">
            <dt class="text-sm font-medium text-slate-600 dark:text-slate-400 hidden sm:block">{{ __('Published') }}</dt>
            <dd class="text-xs text-slate-500 dark:text-slate-300">{{ now()->subYear(1) > $post->published_at ? $post->published_at->isoFormat('LL') : $post->published_at->diffForHumans() }}</dd>
        </div>

        <div class="flex flex-col-reverse">
            <dt class="text-sm font-medium text-slate-600 dark:text-slate-400 hidden sm:block">{{ __('Reading time') }}</dt>
            <dd class="text-xs text-slate-500 dark:text-slate-300">{{ read_time($post->body) }}</dd>
        </div>

        <div class="flex flex-col-reverse">
            <dt class="text-sm font-medium text-slate-600 dark:text-slate-400 hidden sm:block">{{ __('Comments') }}</dt>
            <dd class="text-xs text-slate-500 dark:text-slate-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline-block sm:hidden">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                </svg>
                {{ App\Helpers\NumberHelper::nearestK($post->commentCount()) }}
            </dd>
        </div>

        <div class="flex flex-col-reverse">
            <dt class="text-sm font-medium text-slate-600 dark:text-slate-400 hidden sm:block">{{ __('Views') }}</dt>
            <dd class="text-xs text-slate-500 dark:text-slate-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline-block sm:hidden">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ App\Helpers\NumberHelper::nearestK(views($post)->count()) }}
            </dd>
        </div>
    </dl>
</a>
