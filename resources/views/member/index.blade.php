<x-app-layout>
    <div>
        <div class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
            @component('components.breadcrumb', ['breadcrumbs' => [
                ['url' => null, 'label' => __('Members')],
            ]])
            @endcomponent

            <div class="flex p-4 mb-4 text-sm text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800" role="alert">
                <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Info</span>
                <div>
                    <p class="font-bold">Want to search for a user?</p>
                    Use the search icon in the menu bar or click <kbd class="px-1.5 py-1 mx-0.5 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded-md dark:bg-gray-600 dark:text-gray-100 dark:border-gray-500">Ctrl</kbd> + <kbd class="px-1.5 py-1 mx-0.5 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded-md dark:bg-gray-600 dark:text-gray-100 dark:border-gray-500">K</kbd> or <kbd class="px-1.5 py-1 mx-0.5 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded-md dark:bg-gray-600 dark:text-gray-100 dark:border-gray-500">CMD</kbd> + <kbd class="px-1.5 py-1 mx-0.5 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded-md dark:bg-gray-600 dark:text-gray-100 dark:border-gray-500">K</kbd> to open it, then type "user" and press enter, then type the user you want to search for.
                </div>
            </div>

            <div class="flex flex-col items-center justify-center w-full mx-auto overflow-hidden bg-white rounded-lg shadow-lg dark:bg-gray-900 dark:shadow-gray-700">
                <div class="flex flex-col w-full divide-y divide dark:divide-gray-700">
                    @forelse($users as $user)

                        <a href="{{ route('member.show', $user->username) }}" class="flex hover:bg-green-400/10">
                            <div class="grid items-center w-full grid-rows-2 gap-4 p-3 cursor-pointer select-none md:grid-rows-none md:flex sm:p-4 md:gap-0">
                                <div class="flex-col items-center justify-center hidden w-10 h-10 mr-4 md:flex">
                                    <img alt="{{ $user->username }}" src="{{ $user->profile_photo_url ?? asset('img/TreeLogoSmall.png') }}" class="object-cover w-10 h-10 mx-auto rounded-full "/>
                                </div>
                                <div class="flex-1 md:pl-1 text-gray-800 dark:text-gray-200">
                                    {{ $user->username }}
                                    <p class='text-sm text-gray-300 dark:text-gray-700'>{{ $user->name }}</p>
                                </div>
                                <div class="flex flex-col justify-center text-xs text-gray-400 items-left">
                                    <div class="flex flex-row justify-between min-w-[10rem]">
                                        <span class='pr-1'>@lang('Joined'):</span>
                                        <span>{{ $user->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="flex flex-row justify-between">
                                        <span class='pr-1'>@lang('Last online'):</span>
                                        <span>{{ $user->last_active_at->diffForHumans() ?? trans('Never') }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>

                    @empty
                        <p class='text-gray-300'>No users found</p>
                    @endforelse
                </div>
            </div>
            <div class='mt-6 mb-4'>
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
