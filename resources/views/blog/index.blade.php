<x-guest-layout>

    <x-home.nav-menu />

    <div class="p-4">
        <div class="flex flex-col items-center pt-6 sm:pt-0">

			<div class="w-full p-6 pt-4 mt-6 mb-10 overflow-hidden rounded-lg shadow-md sm:max-w-2xl bg-gray-50 dark:bg-gray-800">

				<ul class="flex mb-8 text-sm text-gray-500 lg:text-base">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}">@lang('Home')</a>
                        <svg class="w-auto h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </li>
                    <li class="inline-flex items-center">
                        <p class="font-bold text-gray-600 dark:text-gray-400">@lang('Blog')</p>
                    </li>
                </ul>

				<div class="flex items-end justify-between">
					<div class="">
						<p class="mb-2 text-4xl font-bold text-gray-800 dark:text-gray-100">
							@lang('Official Blog')
						</p>
						<p class="font-light text-gray-400 text-1xl dark:text-gray-300">
							@lang('This is the official blog of the team and developers of the game.')
						</p>
					</div>
				</div>

			</div>

			<div class="grid w-full grid-cols-1 gap-8 sm:max-w-2xl">
				@empty($posts->count())
					<div class="w-full m-auto overflow-hidden no-underline transition border rounded-lg shadow-lg h-90 border-gray-50 dark:border-gray-900">
						<div class="block w-full h-full">
							<div class="w-full p-4 bg-white dark:bg-gray-800">
								<p class="italic font-light text-gray-400 dark:text-gray-200">@lang('Nothing found.')</p>
							</div>
						</div>
					</div>
				@endempty
				@foreach($posts as $post)
					<div class="w-full m-auto overflow-hidden no-underline transition border rounded-lg shadow-lg h-90 border-gray-50 dark:border-gray-900">
						<div class="block w-full h-full">
							<div class="w-full p-4 bg-white dark:bg-gray-800">
								<p class="font-medium text-green-500 text-md">
									{{ now()->subYear(1) > $post->published_at ? $post->published_at->isoFormat('LL') : $post->published_at->diffForHumans() }}<span class="text-sm text-gray-600 dark:text-gray-400"> &middot; {{ read_time($post->body)}}{!! ($post->tags->count()) ? ' &middot; ' : '' !!}</span>
									@foreach ($post->tags as $tag)
										<span class="inline-flex items-center justify-center px-1 py-1 mr-1 text-xs font-bold leading-none uppercase bg-gray-400 rounded text-gray-50 dark:text-gray-800 dark:bg-gray-400">{{ $tag->name }}</span>
									@endforeach
								</p>
								<a href="{{ route('blog.show', $post->uuid) }}" class="mb-2 text-2xl font-medium text-gray-800 dark:text-white break-word hover:text-gray-500 dark:hover:text-gray-300">
									@if($post->sticky)
										<svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-6 h-6 mr-1 text-red-500" viewBox="0 0 20 20" fill="currentColor">
											<path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd" />
										</svg>
									@endif
									{{ $post->title }}
								</a>
								<div class="pt-2 font-light leading-6 text-gray-500 dark:text-gray-300 text-md">
									<p>{!! strip_tags(Str::of(Str::limit($post->body, 300))->markdown()) !!}</p>
								</div>
								<div class="flex items-center mt-4">
									<a href="{{ route('member.show', $post->user) }}" class="relative block">
										<img alt="{{ $post->username }}" src="{{ $post->user->profile_photo_url ?? asset('img/TreeLogoSmall.png') }}" class="object-cover w-10 h-10 mx-auto rounded-full "/>
									</a>
									<div class="flex flex-col justify-between ml-4 text-sm">
										<p class="text-gray-800 dark:text-white">
											{{ $post->user->username }}
										</p>
										<p class="text-gray-400 dark:text-gray-300">
											<svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
											</svg> {{ App\Helpers\NumberHelper::nearestK($post->likers()->count()) }}
											&nbsp;
											<svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
											</svg> {{ App\Helpers\NumberHelper::nearestK(views($post)->count()) }}

                                            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                            </svg> {{ App\Helpers\NumberHelper::nearestK($post->commentCount()) }}
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				@endforeach
				@if($posts->hasPages())
					<div class="p-4 bg-white rounded-lg dark:bg-gray-800">
						{!! $posts->links() !!}
					</div>
				@endif
			</div>
		</div>
	</div>
</x-guest-layout>
