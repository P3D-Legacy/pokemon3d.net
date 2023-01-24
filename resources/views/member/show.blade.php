<x-app-layout>

    <div class='grid grid-cols-6 gap-6 py-10 max-w-7xl mx-auto'>
        <div class="col-span-2">
            <div class="max-w-2xl">
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
                    <div class="mt-2 text-sm text-gray-400">
                        <div class="flex flex-row items-center ml-auto space-x-2">
                            <div>{{ __('Joined') }}: {{ $user->created_at->diffForHumans() }}</div>
                            <div class="w-1 h-1 bg-gray-400 rounded-full"></div>
                            <div>{{ __('Last online') }}: {{ $user->last_active_at ? $user->last_active_at ->diffForHumans() : trans('Never') }}</div>
                        </div>
                    </div>
                    <div class="flex gap-2 mt-3">
                        @foreach($user->unlockedAchievements() as $achievement)
                            <x-achievement :achievement="$achievement" />
                        @endforeach
                    </div>
                    <div x-data="{ activeTab:1, tabs: [
                        { id: 1, label: '{{ trans('About') }}' },
                        { id: 2, label: '{{ trans('Connected Accounts') }}' },
                    ]}">
                        <ul class="flex items-center w-full my-4">
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
                                            {{ $user->last_active_at->isoFormat('LL') }}
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
                                                    @case(0)
                                                        <span>No selection</span>
                                                        @break
                                                    @case(1)
                                                        <span>Male</span>
                                                        @break
                                                    @case(2)
                                                        <span>Female</span>
                                                        @break
                                                    @case(3)
                                                        <span>Genderless</span>
                                                        @break
                                                    @default
                                                        <span>Unknown</span>
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

        <div class="col-span-4">
            <div class="w-full">
                <div class="flex flex-col p-5 bg-white border rounded-lg dark:bg-slate-900 border-slate-300 dark:border-slate-800">
                    <div class="text-4xl font-semibold text-gray-800 dark:text-slate-200">{{ trans('Game Save') }}</div>
                    @empty($user->gamejolt || $user->gamesave)
                        <p class='text-gray-500 text-sm pt-2'>{{ trans('User has not connected their Game Jolt account yet') }}</p>
                    @endempty
                    @if($user->gamejolt and $user->gamesave)
                        <div x-data="{ activeTab:1, tabs: [
                            { id: 1, label: '{{ trans('In-Game Trophies') }} ({{ $user->gamejolt->trophies->where('achieved', true)->count() }}/{{ $user->gamejolt->trophies->count() }})' },
                        ]}">
                            <ul class="flex items-center w-full my-4">
                                <template x-for="(tab, tab.id) in tabs" :key="tab.id">
                                <li class="px-4 py-2 text-gray-500 border-b-2 cursor-pointer dark:border-gray-800"
                                    :class="activeTab===tab.id ? 'text-green-500 border-green-500 dark:border-green-500' : ''" @click="activeTab = tab.id" x-text="tab.label"></li>
                            </template>
                            </ul>
                            <div class="flex w-full dark:text-slate-50">
                                <div x-show="activeTab===1">
                                    @if($user->gamejolt->trophies->count() > 0)
                                        <div class="grid grid-cols-2 gap-4">
                                            @foreach ($user->gamejolt->trophies as $trophy)
                                                <div
                                                    class="flex flex-col items-center justify-center p-5 rounded-md shadow bg-gray-50 shrink dark:bg-black {{ ($trophy->achieved) ? 'border-2 border-green-500' : '' }}">
                                                    <img src="{{ $trophy->image_url }}" alt="{{ $trophy->title }}" title="{{ $trophy->title }}"
                                                         class="object-cover w-20 h-20 border-2 rounded-md {{ $trophy->difficulty=='Bronze' ? 'border-yellow-800' : '' }}{{ $trophy->difficulty=='Silver' ? 'border-slate-400' : '' }}{{ $trophy->difficulty=='Gold' ? 'border-yellow-500' : '' }}{{ $trophy->difficulty=='Platinum' ? 'border-slate-600' : '' }} {{ $trophy->achieved ? '' : 'grayscale' }}">
                                                    <h4 class="my-2 font-bold underline decoration-2 underline-offset-4 {{ $trophy->difficulty=='Bronze' ? 'text-yellow-800' : '' }}{{ $trophy->difficulty=='Silver' ? 'text-slate-400' : '' }}{{ $trophy->difficulty=='Gold' ? 'text-yellow-500' : '' }}{{ $trophy->difficulty=='Platinum' ? 'text-slate-600' : '' }}">
                                                        @if($trophy->achieved)
                                                            <span class="has-tip">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                     class="inline-block w-5 h-5 text-green-500 dark:text-green-600" viewBox="0 0 20 20"
                                                                     fill="currentColor">
                                                                    <path fill-rule="evenodd"
                                                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                          clip-rule="evenodd" />
                                                                </svg>
                                                                <span
                                                                    class="p-3 -mt-4 -ml-3 text-sm text-gray-900 bg-green-200 rounded tip">{{ __('Achieved') }}</span>
                                                            </span>
                                                        @endif{{ $trophy->title }}
                                                    </h4>
                                                    <div class="text-sm text-center">{{ $trophy->description }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        {{ trans('No Trophies found') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
