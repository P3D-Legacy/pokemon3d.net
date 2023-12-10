<div>
    <div class="max-w-lg">
        <div class="relative p-4 bg-white rounded-lg shadow-md dark:bg-black">
            @auth
                @if(auth()->user()->id == $this->server->user_id)
                    <div x-data="{ dropdownOpen: false }" class="absolute top-2 right-2" x-cloak>
                        <button @click="dropdownOpen = !dropdownOpen" class="relative z-10 block p-1 text-black focus:outline-none dark:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                            </svg>
                        </button>
                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute right-0 z-40 w-48 py-2 mt-2 bg-white border border-slate-200 rounded-md shadow-md dark:bg-slate-900 dark:border-slate-800">
                            @if(!$this->server->active)
                                <button wire:click.defer="reactivate" wire:loading.attr="disabled" class="block w-full px-4 py-2 text-sm text-left text-slate-700 capitalize dark:text-slate-300 hover:bg-green-700 hover:text-white disabled:text-slate-300 disabled:bg-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2d 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    @lang('Reactivate')
                                </button>
                            @endif
                            <a href="{{ route('server.edit', $this->server->uuid) }}" class="block px-4 py-2 text-sm text-slate-700 capitalize dark:text-slate-300 hover:bg-green-700 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                @lang('Edit')
                            </a>
                            <button wire:click.defer="destroy" class="block w-full px-4 py-2 text-sm text-left text-slate-700 capitalize dark:text-slate-300 hover:bg-green-700 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                @lang('Delete')
                            </button>
                        </div>
                    </div>
                @endif
            @endauth
            <div class="relative inline-block">
                <span class="absolute top-0 left-0 w-3 h-3">
                    <span class="animate-ping absolute mt-1 inline-flex h-full w-full rounded-full opacity-75 {{ ($this->server->ping && $this->server->last_check_at > now()->subHours(1)) ? 'bg-green-600' : 'bg-red-600' }}"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 border border-white/75 {{ ($this->server->ping && $this->server->last_check_at > now()->subHours(1)) ? 'bg-green-600' : 'bg-red-600' }}"></span>
                </span>
                <h2 class="pl-2 mb-2 text-2xl font-bold text-slate-800 dark:text-slate-200">{{ $this->server->name }}</h2>
                @if($this->server->official)
                    <div x-data="{ tooltip: false }" class="relative z-30 inline-flex">
                        <span x-on:mouseover="tooltip = true" x-on:mouseleave="tooltip = false" class="inline-flex items-center justify-center px-2 py-1 text-sm font-bold leading-none text-indigo-100 uppercase bg-green-700 rounded"><svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>{{ __('Official') }}</span>
                        <div class="relative" x-cloak x-show.transition.origin.bottom="tooltip">
                            <div class="absolute top-0 z-10 w-16 p-2 -mt-1 text-xs leading-tight text-white transform -translate-x-1/2 -translate-y-full bg-green-600 rounded shadow-md min-w-max">
                                {{ __('This means that the server is maintained by the P3D team') }}
                            </div>
                            <svg class="absolute z-10 w-6 h-6 text-green-600 transform -translate-x-12 -translate-y-3 fill-current stroke-current" width="8" height="8">
                                <rect x="12" y="-10" width="8" height="8" transform="rotate(45)" />
                            </svg>
                        </div>
                    </div>
                @endif
                @if($this->server->created_at > now()->subDays(7))
                    <div x-data="{ tooltip: false }" class="relative z-30 inline-flex">
                        <span x-on:mouseover="tooltip = true" x-on:mouseleave="tooltip = false" class="inline-flex items-center justify-center px-2 py-1 text-sm font-bold leading-none text-red-100 uppercase bg-red-500 rounded dark:bg-red-700"><svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd" /></svg>{{ __('New') }}</span>
                        <div class="relative" x-cloak x-show.transition.origin.bottom="tooltip">
                            <div class="absolute top-0 z-10 w-16 p-2 -mt-1 text-xs leading-tight text-white transform -translate-x-1/2 -translate-y-full bg-red-600 rounded shadow-md min-w-max">
                                {{ __('This server was added in the last seven days') }}
                            </div>
                            <svg class="absolute z-10 w-6 h-6 text-red-600 transform -translate-x-12 -translate-y-3 fill-current stroke-current" width="8" height="8">
                                <rect x="12" y="-10" width="8" height="8" transform="rotate(45)" />
                            </svg>
                        </div>
                    </div>
                @endif
                @if($this->server->ping)
                    <div x-data="{ tooltip: false }" class="relative z-30 inline-flex">
                        <span x-on:mouseover="tooltip = true" x-on:mouseleave="tooltip = false" class="inline-flex items-center justify-center px-2 py-1 text-sm font-bold leading-none text-slate-100 uppercase bg-slate-600 rounded"><svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4 mr-1" viewBox="0 0 20 20" fill="currentColor"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" /></svg>{{ $this->server->ping }}</span>
                        <div class="relative" x-cloak x-show.transition.origin.bottom="tooltip">
                            <div class="absolute top-0 z-10 w-16 p-2 -mt-1 text-xs leading-tight text-white transform -translate-x-1/2 -translate-y-full bg-slate-600 rounded shadow-md min-w-max">
                                {{ __('The ping relative to the server from this website') }}
                            </div>
                            <svg class="absolute z-10 w-6 h-6 text-slate-600 transform -translate-x-12 -translate-y-3 fill-current stroke-current" width="8" height="8">
                                <rect x="12" y="-10" width="8" height="8" transform="rotate(45)" />
                            </svg>
                        </div>
                    </div>
                @endif
                @if(!$this->server->ping && $this->server->last_check_at)
                    <div x-data="{ tooltip: false }" class="relative z-30 inline-flex">
                        <span x-on:mouseover="tooltip = true" x-on:mouseleave="tooltip = false" class="inline-flex items-center justify-center px-2 py-1 text-sm font-bold leading-none text-red-100 uppercase bg-red-600 rounded dark:bg-red-900"><svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.658a9 9 0 01-2.167-9.238m7.824 2.167a1 1 0 111.414 1.414m-1.414-1.414L3 3m8.293 8.293l1.414 1.414" /></svg> {{ __('Offline') }}</span>
                        <div class="relative" x-cloak x-show.transition.origin.bottom="tooltip">
                            <div class="absolute top-0 z-10 w-16 p-2 -mt-1 text-xs leading-tight text-white transform -translate-x-1/2 -translate-y-full bg-red-600 rounded shadow-md min-w-max">
                                {{ __('Last checked: :time', ['time'=>$this->server->last_check_at->diffForHumans()]) }}
                            </div>
                            <svg class="absolute z-10 w-6 h-6 text-red-600 transform -translate-x-12 -translate-y-3 fill-current stroke-current" width="8" height="8">
                                <rect x="12" y="-10" width="8" height="8" transform="rotate(45)" />
                            </svg>
                        </div>
                    </div>
                @endif
                @auth
                    @if($this->server->user_id == auth()->user()->id && !$this->server->active)
                        <div x-data="{ tooltip: false }" class="relative z-30 inline-flex">
                            <span x-on:mouseover="tooltip = true" x-on:mouseleave="tooltip = false" class="inline-flex items-center justify-center px-2 py-1 text-sm font-bold leading-none text-red-100 uppercase bg-red-600 rounded dark:bg-red-900"><svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>{{ __('Deactivated') }}</span>
                            <div class="relative" x-cloak x-show.transition.origin.bottom="tooltip">
                                <div class="absolute top-0 z-10 w-16 p-2 -mt-1 text-xs leading-tight text-white transform -translate-x-1/2 -translate-y-full bg-red-600 rounded shadow-md min-w-max">
                                    {{ __('Deactivated because of downtime for the past 24 hours') }}
                                </div>
                                <svg class="absolute z-10 w-6 h-6 text-red-600 transform -translate-x-12 -translate-y-3 fill-current stroke-current" width="8" height="8">
                                    <rect x="12" y="-10" width="8" height="8" transform="rotate(45)" />
                                </svg>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>
            <p class="text-slate-600">{{ $this->server->description }}</p>
            <p class="font-semibold text-green-600 dark:text-green-300">{{ $this->server->host }}:{{ $this->server->port }}</p>
            @auth
                @if ($this->server->user_id == auth()->user()->id && !$this->server->active)
                    <div class="px-4 py-3 my-3 text-sm leading-normal text-blue-600 bg-blue-100 rounded-lg" role="alert">
                        <p>
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>{{ __('This server has been deactivated from the list because of downtime for the past 24 hours. Only you can see this server now. If it is online again try reactivating it from the dotted-menu.') }}
                        </p>
                    </div>
                @endif
            @endauth
            @if($this->server->official)
                <div class="flex items-center mt-2">
                    <div class="relative inline-block">
                        <img alt="profil" src="{{ asset('img/TreeLogoSmall.png') }}" class="object-cover w-5 h-5 mx-auto rounded-full "/>
                    </div>
                    <div class="flex flex-col justify-between ml-2 text-sm">
                        <p class="text-slate-600 dark:text-white">
                            {{ __('The P3D Team') }}
                        </p>
                    </div>
                </div>
            @else
                <div class="flex items-center mt-2">
                    <a href="{{ route('member.show', $this->server->user) }}" class="flex">
                        <div class="relative inline-block">
                            <img alt="{{ $this->server->user->name }}" src="{{ $this->server->user->profile_photo_url ?? asset('img/TreeLogoSmall.png') }}" class="object-cover w-5 h-5 mx-auto rounded-full "/>
                        </div>
                        <div class="flex flex-col justify-between ml-2 text-sm">
                            <p class="text-green-800 dark:text-white hover:underline">
                                {{ $this->server->user->username }}
                            </p>
                        </div>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
