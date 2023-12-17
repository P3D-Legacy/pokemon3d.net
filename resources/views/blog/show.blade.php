<x-app-layout>
    <div class="px-4 py-10 max-w-3xl mx-auto md:px-6 lg:px-8 sm:pb-16">
        @component('components.breadcrumb', ['breadcrumbs' => [
            ['url' => route('blog.index'), 'label' => __('Blog')],
            ['url' => null, 'label' => $post->title],
        ]])
        @endcomponent

        <div class="rounded-lg bg-white shadow-md dark:bg-slate-900 p-6 sm:p-8 border border-slate-200 dark:border-slate-700">
            <div class="text-sm leading-6">
                <dl>
                    <dt class="sr-only">Date</dt>
                    <dd class="text-slate-700 dark:text-slate-400">
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
                @if($post->tags->count() > 0)
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
                <div class="flex items-center p-2 mt-8 text-sm leading-4 bg-orange-600 rounded-lg text-orange-50 lg:rounded-xl lg:inline-flex sm:text-base" role="alert">
                    <span class="flex px-1 py-1 mr-3 text-xs font-bold text-white uppercase bg-orange-400 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <span class="flex-auto mr-2 font-semibold text-left">{{ __('Please be aware that this article is over a year old, and some of the information it contains may no longer be up-to-date. While we strive to keep our content as current and accurate as possible, we recommend double-checking any important details before relying on them.') }}</span>
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
