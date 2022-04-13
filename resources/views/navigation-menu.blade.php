<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 dark:bg-black dark:border-gray-800">
    <!-- Primary Navigation Menu -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex items-center flex-shrink-0">
                    <a href="{{ route('home') }}">
                        <x-logo-icon class="block w-auto h-8" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-jet-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-jet-nav-link>
                    <x-jet-nav-link href="{{ route('server.index') }}" :active="request()->routeIs('server.*')">
                        {{ __('Servers') }}
                    </x-jet-nav-link>
                    <x-jet-nav-link href="{{ route('resource.index') }}" :active="request()->routeIs('resource.*')">
                        {{ __('Resources') }}
                    </x-jet-nav-link>
                    <x-jet-nav-link href="{{ route('review') }}" :active="request()->routeIs('review')">
                        {{ __('Review') }}
                    </x-jet-nav-link>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-dropdown :active="request()->is('skin*')">
                        <x-slot name="trigger">
                            {{ __('Skins') }}
                        </x-slot>
                        <x-slot name="content">
                            <x-jet-dropdown-link href="{{ route('skin-home') }}">
                                {{ __('My Skins') }}
                            </x-jet-dropdown-link>
                            <div class="block px-4 py-2 text-xs text-gray-400 cursor-default">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>{{ __('Public') }}
                            </div>
                            <x-jet-dropdown-link href="{{ route('skins-popular') }}">
                                {{ __('Most Popular') }}
                            </x-jet-dropdown-link>
                            <x-jet-dropdown-link href="{{ route('skins-newest') }}">
                                {{ __('Newest') }}
                            </x-jet-dropdown-link>
                        </x-slot>
                    </x-nav-dropdown>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="relative ml-3">
                        <x-jet-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition bg-white border border-transparent rounded-md hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50">
                                        {{ Auth::user()->currentTeam->name }}

                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    <x-jet-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('Team Settings') }}
                                    </x-jet-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-jet-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-jet-dropdown-link>
                                    @endcan

                                    <div class="border-t border-gray-100"></div>

                                    <!-- Team Switcher -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Switch Teams') }}
                                    </div>

                                    @foreach (Auth::user()->allTeams() as $team)
                                        <x-jet-switchable-team :team="$team" />
                                    @endforeach
                                </div>
                            </x-slot>
                        </x-jet-dropdown>
                    </div>
                @endif

                <div class="relative ml-3">
                    <x-jet-dropdown align="right" width="60">
                        <x-slot name="trigger">
                            <span class="inline-flex rounded-md">
                                <button id="switchTheme" class="inline-flex items-center p-2 text-sm font-medium leading-4 text-gray-500 transition bg-transparent border border-transparent rounded-full hover:bg-gray-100 hover:text-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" id="theme-toggle-light-icon">
                                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="hidden w-5 h-5" viewBox="0 0 20 20" fill="currentColor" id="theme-toggle-dark-icon">
                                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                                    </svg>
                                </button>
                            </span>
                        </x-slot>
                        <x-slot name="content"></x-slot>
                    </x-jet-dropdown>
                </div>

                <div class="relative ml-3">
                    <x-jet-dropdown align="right" width="60">
                        <x-slot name="trigger">
                            @include('vendor.language.flag')
                        </x-slot>
                        <x-slot name="content">
                            @include('vendor.language.flags')
                        </x-slot>
                    </x-jet-dropdown>
                </div>

                @role('super-admin|admin')
                    <div class="relative ml-3">
                        <x-jet-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition bg-white border border-transparent rounded-md hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ __('Admin') }}
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Content') }}
                                    </div>
                                    @canany(['posts.create','posts.update','posts.destroy'])
                                        <x-jet-dropdown-link href="{{ route('posts.index') }}">
                                            {{ __('Blog Posts') }}
                                        </x-jet-dropdown-link>
                                    @endcanany
                                    @canany(['tags.create','tags.update','tags.destroy'])
                                        <x-jet-dropdown-link href="{{ route('tags.index') }}">
                                            {{ __('Tags') }}
                                        </x-jet-dropdown-link>
                                    @endcanany
                                    @canany(['stats'])
                                        <x-jet-dropdown-link href="{{ route('stats.index') }}">
                                            {{ __('Stats') }}
                                        </x-jet-dropdown-link>
                                    @endcanany
                                    @canany(['categories.create','categories.update','categories.destroy'])
                                        <x-jet-dropdown-link href="{{ route('categories.index') }}">
                                            {{ __('Categories') }}
                                        </x-jet-dropdown-link>
                                    @endcanany
                                    @canany(['manage.users','manage.roles','manage.permissions'])
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('User Management') }}
                                        </div>
                                    @endcanany
                                    @can('manage.users')
                                        <x-jet-dropdown-link href="{{ route('users.index') }}" :active="request()->routeIs('users.index')">
                                            {{ __('Users') }}
                                        </x-jet-dropdown-link>
                                    @endcan
                                    @can('manage.roles')
                                        <x-jet-dropdown-link href="{{ route('roles.index') }}" :active="request()->routeIs('roles.index')">
                                            {{ __('Roles') }}
                                        </x-jet-dropdown-link>
                                    @endcan
                                    @can('manage.permissions')
                                        <x-jet-dropdown-link href="{{ route('permissions.index') }}" :active="request()->routeIs('permissions.index')">
                                            {{ __('Permissions') }}
                                        </x-jet-dropdown-link>
                                    @endcan
                                </div>
                            </x-slot>
                        </x-jet-dropdown>
                    </div>
                @endrole

                <!-- Settings Dropdown -->
                <div class="relative ml-3">
                    <x-jet-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm transition border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300">
                                    <img class="object-cover w-8 h-8 rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none">
                                        {{ Auth::user()->name }}

                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <x-jet-dropdown-link href="{{ route('member.show', auth()->user()) }}">
                                {{ __('Show Profile') }}
                            </x-jet-dropdown-link>

                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-jet-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Edit Profile') }}
                            </x-jet-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures() && auth()->user()->can('api'))
                                <x-jet-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-jet-dropdown-link>
                            @endif

                            <div class="border-t border-gray-100 dark:border-gray-900"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-jet-dropdown-link href="{{ route('logout') }}"
                                         onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-jet-dropdown-link>
                            </form>
                        </x-slot>
                    </x-jet-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center -mr-2 sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 text-gray-400 transition rounded-md hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 dark:focus:bg-gray-800">
                    <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-jet-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-jet-responsive-nav-link>
            <x-jet-responsive-nav-link href="{{ route('server.index') }}" :active="request()->routeIs('server.*')">
                {{ __('Servers') }}
            </x-jet-responsive-nav-link>
            <x-jet-responsive-nav-link href="{{ route('resource.index') }}" :active="request()->routeIs('resource.*')">
                {{ __('Resources') }}
            </x-jet-responsive-nav-link>
            <x-jet-responsive-nav-link href="{{ route('review') }}" :active="request()->routeIs('review')">
                {{ __('Reviews') }}
            </x-jet-responsive-nav-link>
            <x-jet-responsive-nav-link href="{{ route('skin-home') }}" :active="request()->routeIs('skin-home')">
                {{ __('Skins') }}
            </x-jet-responsive-nav-link>
            <x-jet-responsive-nav-link href="{{ route('skins-popular') }}" :active="request()->routeIs('skins-popular')">
                {{ __('Most Popular') }} {{ __('Skins') }}
            </x-jet-responsive-nav-link>
            <x-jet-responsive-nav-link href="{{ route('skins-newest') }}" :active="request()->routeIs('skins-newest')">
                {{ __('Newest') }} {{ __('Skins') }}
            </x-jet-responsive-nav-link>
        </div>

        <!-- Mobile admin menu -->
        @role('super-admin|admin')
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center px-4">
                    <div class="text-base font-medium text-gray-800 dark:text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ __('Admin') }}
                    </div>
                </div>
                <div class="mt-3 space-y-1">
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Content') }}
                    </div>
                    @canany(['posts.create','posts.update','posts.destroy'])
                        <x-jet-responsive-nav-link href="{{ route('posts.index') }}" :active="request()->routeIs('posts.index')">
                            {{ __('Blog Posts') }}
                        </x-jet-responsive-nav-link>
                    @endcanany
                    @canany(['categories.create','categories.update','categories.destroy'])
                        <x-jet-responsive-nav-link href="{{ route('categories.index') }}" :active="request()->routeIs('categories.index')">
                            {{ __('Categories') }}
                        </x-jet-responsive-nav-link>
                    @endcanany
                    @canany(['tags.create','tags.update','tags.destroy'])
                        <x-jet-responsive-nav-link href="{{ route('tags.index') }}" :active="request()->routeIs('tags.index')">
                            {{ __('Tags') }}
                        </x-jet-responsive-nav-link>
                    @endcanany
                    @canany(['stats'])
                        <x-jet-responsive-nav-link href="{{ route('stats.index') }}" :active="request()->routeIs('stats.index')">
                            {{ __('Stats') }}
                        </x-jet-responsive-nav-link>
                    @endcanany
                    @canany(['manage.users','manage.roles','manage.permissions'])
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('User Management') }}
                        </div>
                    @endcanany
                    @can('manage.users')
                        <x-jet-responsive-nav-link href="{{ route('users.index') }}" :active="request()->routeIs('users.index')">
                            {{ __('Users') }}
                        </x-jet-responsive-nav-link>
                    @endcan
                    @can('manage.roles')
                        <x-jet-responsive-nav-link href="{{ route('roles.index') }}" :active="request()->routeIs('roles.index')">
                            {{ __('Roles') }}
                        </x-jet-responsive-nav-link>
                    @endcan
                    @can('manage.permissions')
                        <x-jet-responsive-nav-link href="{{ route('permissions.index') }}" :active="request()->routeIs('permissions.index')">
                            {{ __('Permissions') }}
                        </x-jet-responsive-nav-link>
                    @endcan
                </div>
            </div>
        @endrole

        <button id="switchTheme2" class="block w-full py-2 pl-3 pr-4 text-base font-medium text-gray-600 transition border-l-4 border-transparent dark:text-gray-300 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" id="theme-toggle-light-icon">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" class="hidden w-5 h-5" viewBox="0 0 20 20" fill="currentColor" id="theme-toggle-dark-icon">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
            </svg>
        </button>

        <div class="relative ml-2">
            <x-jet-dropdown align="left">
                <x-slot name="trigger">
                    @include('vendor.language.flag')
                </x-slot>
                <x-slot name="content">
                    @include('vendor.language.flags')
                </x-slot>
            </x-jet-dropdown>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                <div class="flex-shrink-0 mr-3">
                    <img class="object-cover w-10 h-10 rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                </div>
                @endif

                <div>
                    <div class="text-base font-medium text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-jet-responsive-nav-link href="{{ route('member.show', auth()->user()) }}">
                    {{ __('Show Profile') }}
                </x-jet-responsive-nav-link>

                <!-- Account Management -->
                <x-jet-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Edit Profile') }}
                </x-jet-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-jet-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-jet-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-jet-responsive-nav-link href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                    this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-jet-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    <x-jet-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-jet-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-jet-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-jet-responsive-nav-link>
                    @endcan

                    <div class="border-t border-gray-200"></div>

                    <!-- Team Switcher -->
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Switch Teams') }}
                    </div>

                    @foreach (Auth::user()->allTeams() as $team)
                        <x-jet-switchable-team :team="$team" component="jet-responsive-nav-link" />
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</nav>
