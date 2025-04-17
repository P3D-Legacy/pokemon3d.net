<x-app-layout>
    <div class="px-4 py-10 max-w-3xl mx-auto md:px-6 lg:px-8 sm:pb-16">
        @component('components.breadcrumb', ['breadcrumbs' => [
            ['url' => route('blog.index'), 'label' => __('Blog')],
            ['url' => null, 'label' => $post->title],
        ]])
        @endcomponent

        <div class="rounded-lg bg-white shadow-md dark:bg-slate-900 p-6 sm:p-8 border border-slate-200 dark:border-slate-700">
            <div class="text-base leading-6">
                <dl>
                    <dt class="sr-only">Date</dt>
                    <dd class="text-slate-700 dark:text-slate-300 font-semibold">
                        <time datetime="{{ $post->published_at }}">
                            {{ $post->published_at->isoFormat('LLLL') }}
                        </time>
                    </dd>
                </dl>
                @if($post->updated_at)
                    <dl class="text-xs mt-2">
                        <dt class="sr-only">Author</dt>
                        <dd class="text-slate-700 dark:text-slate-400">
                            <span>{{ __('Updated') }}:</span>
                            <time datetime="{{ $post->updated_at }}">{{ $post->updated_at->isoFormat('LLLL') }}</time>
                        </dd>
                    </dl>
                @endif
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight mt-8 text-slate-900 dark:text-slate-200 md:text-3xl break-words">
                @if($post->sticky)
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-6 h-6 mr-1 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z"
                              clip-rule="evenodd" />
                    </svg>
                @endif
                {{ $post->title }}
            </h1>
            <div class="text-sm leading-6 text-slate-700 dark:text-slate-400 mt-2">
                {{ App\Helpers\NumberHelper::nearestK($post->likers->count()) }} {{ __('Likes') }} &middot;
                {{ App\Helpers\NumberHelper::nearestK(views($post)->count()) }} {{ __('Views') }} &middot;
                {{ read_time($post->body) }} &middot;
                {{ App\Helpers\NumberHelper::nearestK($post->commentCount()) }} {{ __('Comments') }}
                @if($post->tags->isNotEmpty())
                    &middot;
                    @foreach ($post->tags as $tag)
                        <span class="inline-block items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3 inline-block ml-1 mr-0.5">
                                <path fill-rule="evenodd" d="M5.25 2.25a3 3 0 00-3 3v4.318a3 3 0 00.879 2.121l9.58 9.581c.92.92 2.39 1.186 3.548.428a18.849 18.849 0 005.441-5.44c.758-1.16.492-2.629-.428-3.548l-9.58-9.581a3 3 0 00-2.122-.879H5.25zM6.375 7.5a1.125 1.125 0 100-2.25 1.125 1.125 0 000 2.25z" clip-rule="evenodd" />
                            </svg>
                            <span class="whitespace-nowrap">{{ $tag->name }}</span>
                        </span>
                    @endforeach
                @endif
            </div>
            <div class="mt-8">
                <ul class="flex flex-wrap text-sm leading-6 -mt-6 -mx-5">
                    <li class="flex items-center font-medium whitespace-nowrap px-5 mt-6">
                        <img src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->username }}" class="mr-3 w-9 h-9 rounded-full bg-slate-50 dark:bg-slate-800" decoding="async">
                        <div class="text-sm leading-4">
                            <div class="text-slate-900 dark:text-slate-200">{{ $post->user->username }}</div>
                            <div class="mt-1"><a href="{{ route('member.show', $post->user->username) }}" class="text-green-500 hover:text-green-600 dark:text-green-400">@<!---->{{ $post->user->username }}</a></div>
                        </div>
                    </li>
                </ul>
            </div>

            @if (now()->subYears(1) > $post->updated_at)
                <div class="rounded-md bg-blue-50 dark:bg-blue-700 p-4 mt-8">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400 dark:text-blue-100" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1 md:flex md:justify-between">
                            <p class="text-sm text-blue-700 dark:text-blue-100 font-semibold">{{ __('Note: This article was published over a year ago. Information within may have changed since then. While efforts are made to keep content current, please verify critical details before making decisions based on this information.') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <article class="mt-16 prose dark:prose-invert dark:text-slate-100 prose-a:text-green-600">
                {!! Str::of($post->body)->markdown() !!}
            </article>

            @livewire('blog.like-button', ['post' => $post])

        </div>

        @livewire('blog.comment-section', ['post' => $post])

    </div>
</x-app-layout>
