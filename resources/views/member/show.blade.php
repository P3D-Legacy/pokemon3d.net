<x-app-layout>

    <div>
        <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto">
                <div class="w-full">
                    <div class="w-full h-48 bg-green-600 rounded-t-lg bg-spring"></div>
                    <div class="absolute ml-5 -mt-20">
                        <div class="overflow-hidden bg-gray-200 border border-b border-gray-300 rounded-lg shadow-md shadow-black/25 w-36 h-36 border-primary">
                            <img class="object-cover w-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col p-5 pt-20 bg-white border rounded-b-lg dark:bg-slate-900 border-slate-300 dark:border-slate-800">
                    <div class="text-4xl font-semibold text-gray-800 dark:text-slate-200">{{ $user->username }}</div>
                    <div class="text-2xl text-gray-800 dark:text-slate-200">{{ $user->name }}</div>
                    <div class="mt-2 text-sm text-gray-400">
                        <div class="flex flex-row items-center ml-auto space-x-2">
                            <div>{{ __('Member since') }}: {{ $user->created_at->diffForHumans() }}</div>
                            {{--
                                <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                <div>{{ __('Last online') }}: {{ $user->created_at->diffForHumans() }}</div>
                            --}}
                        </div>
                    </div>
                    {{--
                        <div class="flex gap-8 pt-8">
                            <div class="flex flex-col">
                                <div class="w-20 h-5 mb-1 bg-gray-200 border border-gray-300"></div>
                                <div class="w-20 h-5 mb-1 bg-gray-200 border border-gray-300"></div>
                            </div>
                            <div class="flex flex-col">
                                <div class="w-20 h-5 mb-1 bg-gray-200 border border-gray-300"></div>
                                <div class="w-20 h-5 mb-1 bg-gray-200 border border-gray-300"></div>
                            </div>
                            <div class="flex flex-col">
                                <div class="w-20 h-5 mb-1 bg-gray-200 border border-gray-300"></div>
                                <div class="w-20 h-5 mb-1 bg-gray-200 border border-gray-300"></div>
                            </div>
                            <div class="flex flex-col">
                                <div class="w-20 h-5 mb-1 bg-gray-200 border border-gray-300"></div>
                                <div class="w-20 h-5 mb-1 bg-gray-200 border border-gray-300"></div>
                            </div>
                        </div>
                        <div class="py-5 break-all">
                            <div class="h-5 mb-1 bg-gray-200 border border-gray-300 w-44"></div>
                            <div class="w-full h-40 mb-1 bg-gray-200 border border-gray-300"></div>
                        </div>
                    --}}
                </div>
        </div>
    </div>
</x-app-layout>
