<x-app-layout>
	<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Post') }}
        </h2>
    </x-slot>
	
	<div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
				<div class="inline-block min-w-full shadow rounded-lg overflow-hidden bg-white p-6">

					<form action="{{ route('posts.update', $post) }}" method="PUT" enctype="multipart/form-data">
						@csrf
						<div class="flex flex-wrap mb-6">
							<div class="w-full md:w-4/5 px-3 mb-6 md:mb-0">
								<label for="title" class="block mb-1 text-gray-700">Title</label>
								<input id="title" name="title" type="text" class="w-full h-10 px-3 mb-2 text-base text-gray-700 placeholder-gray-600 border rounded-lg focus:shadow-outline" value="{{ old('title') ? old('title') : $post->title }}">
                                @error('title')
                                    <span class="text-xs text-red-700">{{ $message }}</span>
                                @enderror
							</div>
							<div class="w-full md:w-1/5 px-3 mb-6 md:mb-0">
								<label for="active" class="block mb-1 text-gray-700">Published?</label>
								<div class="relative inline-block w-full text-gray-700">
									<select class="w-full h-10 pl-3 pr-6 text-base placeholder-gray-600 border rounded-lg appearance-none focus:shadow-outline" id="active" name="active">
										<option value="0" {{ !$post->active ? 'selected' : '' }}>No</option>
										<option value="1" {{ $post->active ? 'selected' : '' }}>Yes</option>
									</select>
									<div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
										<svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
									</div>
								</div>
                                @error('active')
                                    <span class="text-xs text-red-700">{{ $message }}</span>
                                @enderror
							</div>
						</div>
						<div class="flex flex-wrap mb-6">
							<div class="w-full px-3">
								<label for="grid-password" class="block mb-1 text-gray-700">Post body</label>
								<textarea class="input " name="body" id="body">{{ old('body') ? old('body') : $post->body }}</textarea>
                                @error('body')
                                    <span class="text-xs text-red-700">{{ $message }}</span>
                                @enderror
							</div>
						</div>

                        <div class="flex items-center justify-end text-right px-3">
                            <x-jet-button>
                                {{ __('Save') }}
                            </x-jet-button>
                        </div>
					</form>

				</div>
			</div>
		</div>
	</div>

{{-- Import CSS and JS for SimpleMDE editor --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>

<script>
	// Initialise editors
	var bodyEditor = new SimpleMDE({ element: document.getElementById("body") });
</script>

</x-app-layout>