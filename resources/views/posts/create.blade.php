<x-app-layout>
<section class="w-full">

	<h3 class="text-center text-3xl font-semibold">New Post</h3>

	<form class="w-full px-6"  action="{{ route('posts.store') }}" method="post" enctype="multipart/form-data">
		@csrf
		<div class="flex flex-wrap -mx-3 mb-6">
		<div class="w-full md:w-4/5 px-3 mb-6 md:mb-0">
			<label class="label" for="title">
			Title
			</label>
			<input class="input" id="title" name="title" type="text" >
		</div>
		<div class="w-full md:w-1/5 px-3 mb-6 md:mb-0">
			<label class="label" for="active">
			Active?
			</label>
			<div class="relative">
			<select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight " id="active" name="active">
				<option value="0">No</option>
				<option value="1">Yes</option>
			</select>
			<div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
				<svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
			</div>
			</div>
		</div>
		</div>
		<div class="flex flex-wrap -mx-3 mb-6">
		<div class="w-full px-3">
			<label class="label" for="grid-password">
			Post body
			</label>
			<p class="text-gray-600 text-xs italic mb-2">This is actual post body</p>
			<textarea class="input " name="body" id="body">{{ old('body') }}</textarea>
		</div>
		</div>

		<div class="w-full text-right pa-3 mb-6">
			<input class="btn btn-green my-4" type="submit" value="Save post">
		</div>
	</form>

</section>

{{-- Import CSS and JS for SimpleMDE editor --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>

<script>
	// Initialise editors
	var bodyEditor = new SimpleMDE({ element: document.getElementById("body") });
</script>

</x-app-layout>