<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            Resources <a href="{{ route('resource.create') }}" class="px-2 py-1 ml-4 text-sm font-bold text-white bg-green-500 rounded hover:bg-green-700"><svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg> Create</a>
        </h2>
    </x-slot>

    <div>
        <div class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
            @component('components.breadcrumb', ['breadcrumbs' => [
                ['url' => null, 'label' => 'Resources'],
            ]])
            @endcomponent
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 md:grid-cols-4 grid-flow-cols">
                <div class="bg-red-100">
                    Category
                </div>
                <div class="sm:col-span-2 md:col-span-3">
            
                    <div class="flex flex-col items-center justify-center w-full mx-auto overflow-hidden bg-white rounded-lg shadow-lg dark:bg-gray-900 dark:shadow-gray-700">
                        <div class="w-full px-4 py-5 border-b sm:px-6 dark:border-gray-700">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                                Resources
                            </h3>
                        </div>
                        <div class="flex flex-col w-full divide-y divide dark:divide-gray-700">
                            @foreach ($resources as $resource)
                                <a href="{{ route('resource.show', $resource) }}" class="flex hover:bg-green-400/10">
                                    <div class="flex items-center w-full p-4 cursor-pointer select-none">
                                        <div class="flex flex-col items-center justify-center w-10 h-10 mr-4">
                                            <img alt="{{ $resource->user->name }}" src="{{ $resource->user->profile_photo_url ?? asset('img/TreeLogoSmall.png') }}" class="object-cover w-10 h-10 mx-auto rounded-full "/>
                                        </div>
                                        <div class="flex-1 pl-1 mr-16">
                                            <div class="font-medium dark:text-white">
                                                {{ $resource->name }} <span class="text-gray-400 dark:text-gray-500">1.2.3</span>
                                            </div>
                                            <div class="text-sm text-gray-400 dark:text-gray-200">
                                                {{ $resource->user->username }} &middot; {{ $resource->created_at->diffForHumans() }} &middot; CategoryName
                                            </div>
                                            <div class="text-xs text-gray-500 truncate dark:text-gray-300">
                                                {{ $resource->breif }}
                                            </div>
                                        </div>
                                        <div class="flex flex-col justify-center text-xs text-gray-400 basis-1/5 items-left">
                                            <div class="flex flex-row justify-between">
                                                <span>Rating:</span>
                                                <span>4.2/5</span>
                                            </div>
                                            <div class="flex flex-row justify-between">
                                                <span>Likes:</span>
                                                <span>{{ $resource->likers()->count() }}</span>
                                            </div>
                                            <div class="flex flex-row justify-between">
                                                <span>Donwloads:</span>
                                                <span>404</span>
                                            </div>
                                            <div class="flex flex-row justify-between">
                                                <span>Updated:</span>
                                                <span>{{ $resource->updated_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>