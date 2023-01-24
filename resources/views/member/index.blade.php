<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            @lang('Members')
        </h2>
    </x-slot>

    <div>
        <div class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
            @component('components.breadcrumb', ['breadcrumbs' => [
                ['url' => null, 'label' => __('Members')],
            ]])
            @endcomponent
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
