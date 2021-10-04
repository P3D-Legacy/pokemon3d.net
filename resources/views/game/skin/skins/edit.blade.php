<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{'Edit Skin'}}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <form role="form" action="{{ route('skin-update', $skin->uuid) }}" method="post">
                <div class="mb-3">
                    <label for="formName" class="text-gray-700">Name</label>
                    <input class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" id="formName" name="name" value="{{ old('name') ? old('name') : $skin->name }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <input class="text-green-600 border-gray-300 rounded shadow-sm focus:border-green-300 focus:ring focus:ring-offset-0 focus:ring-green-200 focus:ring-opacity-50" type="checkbox" id="checkPublic" name="public" @if (old('public') ?? $skin->public) {{'checked'}} @endif>
                    <label class="text-gray-700" for="checkPublic">Public <span class="ml-2 text-sm text-gray-500">Other users will be able to see this skin</span></label>
                    @error('public')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @csrf
                <button type="submit" class="flex items-center justify-center w-full px-4 py-2 text-base font-semibold text-center text-white transition duration-200 ease-in bg-green-600 rounded-lg shadow-md hover:bg-green-700 focus:ring-green-500 focus:ring-offset-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Update
                </button>
            </form>
        </div>
    </div>
</x-app-layout>