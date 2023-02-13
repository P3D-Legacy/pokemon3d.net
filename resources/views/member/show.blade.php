<x-app-layout>
    <div class='grid grid-cols-1 md:grid-cols-6 gap-6 py-10 max-w-7xl mx-auto px-4 sm:px-6 md:px-2 lg:px-0'>
        <div class="col-span-1 md:col-span-2">
            <div>
                <div class="w-full">
                    <div class="w-full h-48 bg-green-600 rounded-t-lg bg-spring"></div>
                    <div class="absolute ml-5 -mt-20">
                        <div class="overflow-hidden bg-gray-200 border rounded-lg shadow-md shadow-black/25 w-36 h-36 border-gray-400">
                            <img class="object-cover w-full h-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col p-5 pt-20 bg-white border rounded-b-lg dark:bg-slate-900 border-slate-300 dark:border-slate-800">
                    <div class="text-4xl font-semibold text-gray-800 dark:text-slate-200">{{ $user->username }}</div>
                    <div class="gap-2 mt-3 grid grid-cols-6 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8">
                        @foreach($user->unlockedAchievements() as $achievement)
                            <x-achievement :achievement="$achievement" />
                        @endforeach
                    </div>
                    <div x-data="{ activeTab:1, tabs: [
                        { id: 1, label: '{{ trans('About') }}' },
                        { id: 2, label: '{{ trans('Connected Accounts') }}' },
                    ]}">
                        <ul class="flex items-center w-full my-4 overflow-auto">
                            <template x-for="(tab, tab.id) in tabs" :key="tab.id">
                                <li class="px-4 py-2 text-gray-500 border-b-2 cursor-pointer dark:border-gray-800"
                                    :class="activeTab===tab.id ? 'text-green-500 border-green-500 dark:border-green-500' : ''" @click="activeTab = tab.id" x-text="tab.label"></li>
                            </template>
                        </ul>
                        <div class="flex w-full dark:text-slate-50">
                            <div x-show="activeTab===1" class='w-full'>
                                <div class="overflow-hidden shadow sm:rounded-lg dark:bg-black">
                                    <dl>
                                        @if($user->settings()->get('name'))
                                            <x-profile.user-detail title='Full name'>
                                                {{ $user->name }}
                                            </x-profile.user-detail>
                                        @endif
                                        <x-profile.user-detail title='Joined'>
                                            {{ $user->created_at->isoFormat('LL') }}
                                        </x-profile.user-detail>
                                        <x-profile.user-detail title='Last online'>
                                            {{ now()->subDay(1) > $user->last_active_at ? $user->last_active_at->isoFormat('LL') : $user->last_active_at->diffForHumans() }}
                                        </x-profile.user-detail>
                                        @if($user->birthdate && $user->settings()->get('birthdate') || $user->birthdate && $user->settings()->get('age'))
                                            <x-profile.user-detail title='Birthday'>
                                                <div>{{ $user->settings()->get('birthdate') ? $user->birthdate->isoFormat('LL') : '' }}</div>
                                                <div>{{ $user->settings()->get('age') ? $user->birthdate->age.' '.trans('years old') : '' }}</div>
                                            </x-profile.user-detail>
                                        @endif
                                        @if($user->location)
                                            <x-profile.user-detail title='Location'>
                                                {{ $user->location }}
                                            </x-profile.user-detail>
                                        @endif
                                        @if($user->gender)
                                            <x-profile.user-detail title='Gender'>
                                                @switch($user->gender)
                                                    @case(1)
                                                        <span>{{ trans('Male') }}</span>
                                                        @break
                                                    @case(2)
                                                        <span>{{ trans('Female') }}</span>
                                                        @break
                                                    @case(3)
                                                        <span>{{ trans('Genderless') }}</span>
                                                        @break
                                                    @default
                                                        <span>{{ trans('Unknown') }}</span>
                                                @endswitch
                                            </x-profile.user-detail>
                                        @endif
                                        @if($user->about)
                                            <x-profile.user-detail title='About'>
                                                {{ $user->about }}
                                            </x-profile.user-detail>
                                        @endif
                                    </dl>
                                </div>
                            </div>
                            <div x-show="activeTab===2" class='w-full'>
                                <div class="overflow-hidden shadow sm:rounded-lg dark:bg-black">
                                    <dl>
                                        @if($user->gamejolt)
                                            <x-profile.user-detail title='Game Jolt'>
                                                <a href="https://gamejolt.com/{{ '@'.$user->gamejolt->username }}" target="_blank" rel="noopener noreferrer" class='hover:text-green-500'>{{ $user->gamejolt->username }}</a>
                                            </x-profile.user-detail>
                                        @endif
                                        @if($user->twitter)
                                            <x-profile.user-detail title='Twitter'>
                                                <a href="https://twitter.com/{{ $user->twitter->username }}" target="_blank" rel="noopener noreferrer" class='hover:text-green-500'>{{ $user->twitter->username }}</a>
                                            </x-profile.user-detail>
                                        @endif
                                        @if($user->discord)
                                            <x-profile.user-detail title='Discord'>
                                                <a href="https://discord.com/users/{{ $user->discord->id }}" target="_blank" rel="noopener noreferrer" class='hover:text-green-500'>{{ $user->discord->username }}#{{ $user->discord->discriminator }}</a>
                                            </x-profile.user-detail>
                                        @endif
                                        @if($user->twitch)
                                            <x-profile.user-detail title='Twitch'>
                                                <a href="https://twitch.tv/{{ $user->twitch->username }}" target="_blank" rel="noopener noreferrer" class='hover:text-green-500'>{{ $user->twitch->username }}</a>
                                            </x-profile.user-detail>
                                        @endif
                                        @if($user->facebook)
                                            <x-profile.user-detail title='Facebook'>
                                                {{ $user->facebook->name }}
                                            </x-profile.user-detail>
                                        @endif
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-1 md:col-span-4">
            <div class="w-full">
                <div class="flex flex-col p-5 bg-white border rounded-lg dark:bg-slate-900 border-slate-300 dark:border-slate-800">
                    <div class="text-4xl font-semibold text-gray-800 dark:text-slate-200">{{ trans('Game Save') }}</div>
                    @livewire('profile.game-save.main', ['user' => $user])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
