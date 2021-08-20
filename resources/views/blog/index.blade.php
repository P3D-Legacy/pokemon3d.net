<x-guest-layout>
    <div class="pt-4">
        <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
			<div>
                <a href="{{ route('home') }}">
                    <x-logo-small />
                </a>
            </div>

			<div class="w-full sm:max-w-4xl mt-6 mb-10 p-6 pt-4 bg-gray-50 dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">

				<ul class="flex text-gray-500 text-sm lg:text-base mb-8">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}">Home</a>
                        <svg class="h-5 w-auto text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </li>
                    <li class="inline-flex items-center">
                        <p class="text-gray-300">Blog</p>
                    </li>
                </ul>

				<div class="flex items-end justify-between mb-6">
					<div class="">
						<p class="text-4xl font-bold text-gray-800 dark:text-gray-100 mb-2">
							Official Blog
						</p>
						<p class="text-1xl font-light text-gray-400 dark:text-gray-300">
							Here is the official blog of the team.
						</p>
					</div>
				</div>
				<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
					@empty($posts->count())
						<p class="text-gray-400 dark:text-gray-600 font-light italic">Seems like there is nothing here.</p>
					@endempty
					@foreach ($posts as $post)
						<div class="overflow-hidden shadow-lg rounded-lg h-90 w-full m-auto border border-gray-50 dark:border-gray-900">
							<div class="w-full block h-full">
								<div class="bg-white dark:bg-gray-700 w-full p-4">
									<p class="text-green-500 text-md font-medium">
										{{ $post->created_at->diffForHumans() }}<span class="text-gray-300 dark:text-gray-500 text-sm"> &middot; {{ read_time($post->body)}}</span>
									</p>
									<a href="{{ route('blog.show', $post->uuid) }}" class="text-gray-800 dark:text-white text-xl font-medium mb-2 truncate">
										{{ $post->title }}
									</a>
									<div class="text-gray-400 dark:text-gray-300 font-light text-md leading-none pt-2">
										{!! strip_tags(Str::of(Str::limit($post->body, 100))->markdown()) !!}
									</div>
									<div class="flex items-center mt-4">
										<a href="#" class="block relative">
											<img alt="profil" src="{{ asset('img/TreeLogoSmall.png') }}" class="mx-auto object-cover rounded-full h-10 w-10 "/>
										</a>
										<div class="flex flex-col justify-between ml-4 text-sm">
											<p class="text-gray-800 dark:text-white">
												{{ $post->user->username }}
											</p>
											<p class="text-gray-400 dark:text-gray-300">
												<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
												</svg> 1234
												&nbsp;
												<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
												</svg> 1234
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
</x-guest-layout>