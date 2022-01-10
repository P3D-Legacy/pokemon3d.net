<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Resources') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-5xl py-10 mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-flow-col grid-cols-3 gap-4 mb-4">
                <div class="col-span-2 text-2xl dark:text-white">
                    {{ $resource->name }} <span class="text-gray-400 dark:text-gray-500">1.2.3</span>
                </div>
                <div class="justify-end gap-1 text-sm">
                    @livewire('resource.resource-like', ['resource' => $resource])
                    <button class="px-2 py-1 transition-colors duration-150 rounded-md text-sky-100 bg-sky-600 focus:shadow-outline hover:bg-sky-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        Leave a rating
                    </button>
                    <button class="px-2 py-1 text-green-100 transition-colors duration-150 bg-green-600 rounded-md focus:shadow-outline hover:bg-green-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download
                    </button>
                    <button class="px-2 py-1 text-gray-100 transition-colors duration-150 bg-gray-600 rounded-md focus:shadow-outline hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="w-full p-4 bg-white rounded-lg shadow-lg dark:bg-gray-900 dark:shadow-gray-700">
                <div class="grid grid-flow-col grid-cols-4 gap-4">
                    <div class="col-span-3">
                        <div class="prose dark:prose-invert">
                            {!! Str::of($resource->description)->markdown() !!}
                        </div>
                    </div>
                    <div class="flex flex-col justify-center p-4 text-xs text-gray-500 bg-gray-100 rounded dark:bg-gray-800 dark:text-gray-300 items-left">
                        <div class="flex flex-row justify-between">
                            <span>Author:</span>
                            <span>{{ $resource->user->username }}</span>
                        </div>
                        <div class="flex flex-row justify-between">
                            <span>Rating:</span>
                            <span>4.2/5</span>
                        </div>
                        <div class="flex flex-row justify-between">
                            <span>Likes:</span>
                            <span>10</span>
                        </div>
                        <div class="flex flex-row justify-between">
                            <span>Donwloads:</span>
                            <span>404</span>
                        </div>
                        <div class="flex flex-row justify-between">
                            <span>Created:</span>
                            <span>{{ $resource->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex flex-row justify-between">
                            <span>Updated:</span>
                            <span>{{ $resource->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col items-center justify-center w-full mx-auto mt-10 overflow-hidden bg-white rounded-lg shadow-lg dark:bg-gray-900 dark:shadow-gray-700">
                <div class="w-full px-4 py-5 border-b sm:px-6 dark:border-gray-700">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                        Files
                    </h3>
                </div>
                <div class="flex flex-col w-full divide-y divide dark:divide-gray-700">
                    @foreach ([1,2,3] as $file)
                        <div class="flex items-center justify-between w-full p-4 dark:text-white">
                            <div class="flex">
                                1.2.3
                            </div>
                            <div class="flex">
                                1. Jan 1970
                            </div>
                            <div class="flex">
                                13245 downloads
                            </div>
                            <div class="flex">
                                <button class="px-2 py-1 text-sm text-green-100 transition-colors duration-150 bg-green-600 rounded-md focus:shadow-outline hover:bg-green-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Download
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>