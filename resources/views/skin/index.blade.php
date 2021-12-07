<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('My Skins') }} <a href="{{ route('skin-create') }}" class="px-2 py-1 ml-4 text-sm font-bold text-white bg-green-500 rounded hover:bg-green-700"><svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg> {{ __('Create') }}</a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-flow-col grid-cols-3 gap-4">
                <div>
                    <div class="w-full m-auto overflow-hidden rounded-lg shadow-lg h-90">
                        <div class="block w-full h-full">
                            <div class="w-full p-4 bg-white dark:bg-gray-900">
                                <div class="mb-2 text-xl font-medium text-gray-800 dark:text-white">Current In-game Skin</div>
                                <div class="card-body">
                                    @if(File::exists(public_path('player/'.Auth::user()->gamejolt->id.'.png')))
                                        <img src="{{ asset('player/'.Auth::user()->gamejolt->id.'.png') }}?r={{ now()->timestamp }}" class="mx-auto my-4">
                                    @else
                                        <p>No skins have been added to your account yet.</p>
                                        @if(Auth::user()->gamejolt->skins()->count() >= env('SKIN_MAX_UPLOAD'))
                                            <div class="px-4 py-3 mt-4 text-sm leading-normal text-blue-800 bg-blue-300 rounded-lg">
                                                <i class="mr-2 fas fa-info-circle" aria-hidden="true"></i> Your slots are full. You cannot import from the old site unless you delete one of the slots.
                                            </div>
                                        @else
                                            <p class="mt-3 text-sm">
                                                <a href="{{ route('import', Auth::user()->gamejolt->id) }}" class="items-center inline-block w-full px-4 py-2 mt-2 text-blue-900 transition-colors duration-150 bg-blue-200 rounded focus:shadow-outline hover:bg-blue-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    Check for skin to import from the old site
                                                </a>
                                            </p>
                                        @endif
                                    @endif
                                </div>
                                @if(File::exists(public_path('player/'.Auth::user()->gamejolt->id.'.png')))
                                    <div class="mt-2">
                                        <a class="inline-flex items-center h-8 px-4 text-sm text-blue-100 transition-colors duration-150 bg-blue-700 rounded focus:shadow-outline hover:bg-blue-800" href="{{ route('player-skin-duplicate') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-7a2 2 0 012-2h2m3-4H9a2 2 0 00-2 2v7a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-1m-1 4l-3 3m0 0l-3-3m3 3V3" />
                                            </svg>
                                            Save to "My skins"
                                        </a>
                                        @if(File::exists(public_path('player/'.Auth::user()->gamejolt->id.'.png')))
                                            <a class="inline-flex items-center h-8 px-4 ml-1 text-sm text-red-100 transition-colors duration-150 bg-red-700 rounded focus:shadow-outline hover:bg-red-800" href="{{ route('player-skin-destroy') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="w-full m-auto mt-4 overflow-hidden rounded-lg shadow-lg h-90">
                        <div class="block w-full h-full">
                            <div class="w-full p-4 bg-white dark:bg-gray-900">
                                <div class="mb-2 text-xl font-medium text-gray-800 dark:text-white">Admin Skin Deletion Activity</div>
                                <div class="text-sm text-gray-800 dark:text-white">
                                    @if($activity->count())
                                        @foreach ($activity as $log)
                                            <p class="m-0"><span class="text-gray-500">{{ $log->created_at }}:</span> {{ $log->properties['reason'] }}</p>
                                        @endforeach
                                    @else
                                        <p class="m-0">Nothing found.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full m-auto mt-4 overflow-hidden rounded-lg shadow-lg h-90">
                        <div class="block w-full h-full">
                            <div class="w-full p-4 bg-white dark:bg-gray-900">
                                <div class="mb-2 text-xl font-medium text-gray-800 dark:text-white">Skin Information</div>
                                <div class="font-light text-gray-800 dark:text-gray-300 text-md">
                                    <p>Want to make your own skin? <a href="{{ asset('img/template.png') }}" class="text-green-500">Download this template</a> to get started.</p>
                                    <h6 class="mt-1 font-bold text-gray-700 dark:text-gray-200">File Validation</h6>
                                    <ul class="pl-6 list-disc">
                                        <li>Less than 2MB</li>
                                        <li>Has to be a PNG-file</li>
                                        <li>Dimensions ratio of 3/4</li>
                                    </ul>
                                    <h6 class="mt-1 font-bold text-gray-700 dark:text-gray-200">Rules</h6>
                                    <ul class="pl-6 list-disc">
                                        <li>Every part (for a 96x128 sprite, every 32x32 portion) of the skin has to contain at least one pixel that is not transparent.</li>
                                        <li>You have to own the rights to use the image you upload.</li>
                                        <li>The image must not contain any sexual or harassing content.</li>
                                    </ul>
                                    <p class="mt-2 text-sm text-gray-500">If all of the above rules apply to your skin and you upload it, you transfer all rights to the P3D Team. We can alter and delete your skin as long as it stays on our servers.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-2">
                    <h1 class="pb-1 mb-4 text-xl font-semibold leading-tight text-gray-800 border-b dark:text-white dark:border-gray-600">
                        {{ __('Slots') }}: {{ Auth::user()->gamejolt->skins()->count() }} / {{ env('SKIN_MAX_UPLOAD') }}
                    </h1>
                    <div class="grid grid-flow-row grid-cols-1 gap-4 auto-rows-max sm:grid-cols-2">
                        @foreach($skins as $skin)
                            @include('skin.component.card', ['skin' => $skin])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>