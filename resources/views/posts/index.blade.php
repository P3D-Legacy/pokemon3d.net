<x-app-layout>
	<x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Blog Posts') }}
            <button onclick="Livewire.emit('openModal', 'blog.post-form')" class="px-2 py-1 ml-4 text-sm font-bold text-white bg-green-500 rounded hover:bg-green-700"><svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg> @lang('Create')</button>
        </h2>
    </x-slot>

	<div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
			<div class="px-4 py-4 -mx-4 overflow-x-auto sm:-mx-8 sm:px-8">
				<div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
					<table class="min-w-full leading-normal">
						<thead>
							<tr>
								<th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
									@lang('Title')
								</th>
								<th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
									@lang('Published')
								</th>
								<th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
									@lang('Created')
								</th>
								<th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
									@lang('Updated')
								</th>
								<th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
									@lang('Status')
								</th>
								<th scope="col" class="px-5 py-3 text-sm font-normal text-left text-gray-800 uppercase bg-white border-b border-gray-200">
								</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($posts as $post)
								<tr>
									<td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
										<p class="text-gray-900 whitespace-no-wrap">
											{{ Str::limit($post->title, 100) }}
										</p>
									</td>
									<td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
										<p class="text-gray-900 whitespace-no-wrap">
											{{ $post->published_at ? $post->published_at->setTimezone(auth()->user()->timezone)->diffForHumans() : 'Not published' }}
										</p>
									</td>
									<td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
										<p class="text-gray-900 whitespace-no-wrap">
											{{ $post->created_at->setTimezone(auth()->user()->timezone)->diffForHumans() }}
										</p>
									</td>
									<td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
										<p class="text-gray-900 whitespace-no-wrap">
											{{ $post->updated_at->setTimezone(auth()->user()->timezone)->diffForHumans() }}
										</p>
									</td>
									<td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
										@if($post->active)
											<span class="inline-flex items-center justify-center px-2 py-1 text-xs leading-none text-green-900 capitalize bg-green-200 rounded-full">
												<svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
												  </svg>@lang('Visible')
											</span>
										@else
											<span class="inline-flex items-center justify-center px-2 py-1 text-xs leading-none text-gray-900 capitalize bg-gray-200 rounded-full">
												<svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
												</svg>@lang('Draft')
											</span>
										@endif
										@if($post->sticky)
											<span class="inline-flex items-center justify-center px-2 py-1 text-xs leading-none text-red-900 capitalize bg-red-200 rounded-full">
												<svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
												</svg>@lang('Sticky')
											</span>
										@endif
									</td>
									<td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
										<a href="{{ route('blog.show', $post->uuid) }}" class="w-full px-2 py-1 mr-1 text-sm font-semibold text-center text-white transition duration-200 ease-in bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:ring-blue-500 focus:ring-offset-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2">
											<svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
											</svg>
											@lang('Show')
										</a>
										<button onclick="Livewire.emit('openModal', 'blog.post-form', {{ json_encode(['post' => $post->id]) }})" class="px-2 py-1 mr-1 text-sm font-semibold text-center text-white transition duration-200 ease-in bg-yellow-600 rounded-lg shadow-md hover:bg-yellow-700 focus:ring-yellow-500 focus:ring-offset-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2">
											<svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
											</svg>
											@lang('Edit')
										</button>
										<a href="#" class="w-full px-2 py-1 mr-1 text-sm font-semibold text-center text-white transition duration-200 ease-in bg-red-600 rounded-lg shadow-md hover:bg-red-700 focus:ring-red-500 focus:ring-offset-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2">
											<svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
											</svg>
											@lang('Delete')
										</a>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>

				</div>
				{!! $posts->links() !!}
			</div>
		</div>
	</div>
</x-app-layout>
