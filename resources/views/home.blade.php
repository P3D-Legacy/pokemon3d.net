<x-guest-layout>
    <!--Nav-->
    <nav id="header" class="top-0 z-30 w-full py-1 text-white lg:py-6" x-data="{open:false}">
        <div class="container flex flex-wrap items-center justify-between w-full px-2 py-2 mx-auto mt-0 lg:py-6">
            <div class="flex items-center pl-4">
                <a class="font-mono text-2xl font-bold text-white no-underline hover:no-underline lg:text-4xl" href="#">
                    <x-logo-small class="" />
                </a>
            </div>
            
            <div class="block pr-4 lg:hidden">
                <button @click="open = ! open" id="nav-toggle" class="flex items-center px-2 py-1 text-white border border-white rounded appearance-none hover:text-gray-200 hover:border-gray-200 focus:outline-none">
                    <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{'flex': open, 'hidden': ! open}" class="hidden w-full pt-2">
                <ul class="grid flex-1 space-y-1 list-reset lg:flex place-items-end justify-items-end">
                    <x-home.responsive-nav-link title="{{ __('Blog') }}" url="{{ route('blog.index') }}">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </x-slot>
                    </x-home.responsive-nav-link>
                    <x-home.responsive-nav-link title="Forum" url="https://pokemon3d.net/forum/">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                            </svg>
                        </x-slot>
                    </x-home.responsive-nav-link>
                    <x-home.responsive-nav-link title="Wiki" url="https://pokemon3d.net/wiki/">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </x-slot>
                    </x-home.responsive-nav-link>
                    <x-home.responsive-nav-link title="Github" url="https://github.com/P3D-Legacy/P3D-Legacy">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 1792 1792"><path d="M896 128q209 0 385.5 103t279.5 279.5 103 385.5q0 251-146.5 451.5t-378.5 277.5q-27 5-40-7t-13-30q0-3 .5-76.5t.5-134.5q0-97-52-142 57-6 102.5-18t94-39 81-66.5 53-105 20.5-150.5q0-119-79-206 37-91-8-204-28-9-81 11t-92 44l-38 24q-93-26-192-26t-192 26q-16-11-42.5-27t-83.5-38.5-85-13.5q-45 113-8 204-79 87-79 206 0 85 20.5 150t52.5 105 80.5 67 94 39 102.5 18q-39 36-49 103-21 10-45 15t-57 5-65.5-21.5-55.5-62.5q-19-32-48.5-52t-49.5-24l-20-3q-21 0-29 4.5t-5 11.5 9 14 13 12l7 5q22 10 43.5 38t31.5 51l10 23q13 38 44 61.5t67 30 69.5 7 55.5-3.5l23-4q0 38 .5 88.5t.5 54.5q0 18-13 30t-40 7q-232-77-378.5-277.5t-146.5-451.5q0-209 103-385.5t279.5-279.5 385.5-103zm-477 1103q3-7-7-12-10-3-13 2-3 7 7 12 9 6 13-2zm31 34q7-5-2-16-10-9-16-3-7 5 2 16 10 10 16 3zm30 45q9-7 0-19-8-13-17-6-9 5 0 18t17 7zm42 42q8-8-4-19-12-12-20-3-9 8 4 19 12 12 20 3zm57 25q3-11-13-16-15-4-19 7t13 15q15 6 19-6zm63 5q0-13-17-11-16 0-16 11 0 13 17 11 16 0 16-11zm58-10q-2-11-18-9-16 3-14 15t18 8 14-14z"></path></svg>
                        </x-slot>
                    </x-home.responsive-nav-link>
                    <x-home.responsive-nav-link title="Discord" url="http://www.discord.me/p3d">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 71 55">
                                <path d="M60.1045 4.8978C55.5792 2.8214 50.7265 1.2916 45.6527 0.41542C45.5603 0.39851 45.468 0.440769 45.4204 0.525289C44.7963 1.6353 44.105 3.0834 43.6209 4.2216C38.1637 3.4046 32.7345 3.4046 27.3892 4.2216C26.905 3.0581 26.1886 1.6353 25.5617 0.525289C25.5141 0.443589 25.4218 0.40133 25.3294 0.41542C20.2584 1.2888 15.4057 2.8186 10.8776 4.8978C10.8384 4.9147 10.8048 4.9429 10.7825 4.9795C1.57795 18.7309 -0.943561 32.1443 0.293408 45.3914C0.299005 45.4562 0.335386 45.5182 0.385761 45.5576C6.45866 50.0174 12.3413 52.7249 18.1147 54.5195C18.2071 54.5477 18.305 54.5139 18.3638 54.4378C19.7295 52.5728 20.9469 50.6063 21.9907 48.5383C22.0523 48.4172 21.9935 48.2735 21.8676 48.2256C19.9366 47.4931 18.0979 46.6 16.3292 45.5858C16.1893 45.5041 16.1781 45.304 16.3068 45.2082C16.679 44.9293 17.0513 44.6391 17.4067 44.3461C17.471 44.2926 17.5606 44.2813 17.6362 44.3151C29.2558 49.6202 41.8354 49.6202 53.3179 44.3151C53.3935 44.2785 53.4831 44.2898 53.5502 44.3433C53.9057 44.6363 54.2779 44.9293 54.6529 45.2082C54.7816 45.304 54.7732 45.5041 54.6333 45.5858C52.8646 46.6197 51.0259 47.4931 49.0921 48.2228C48.9662 48.2707 48.9102 48.4172 48.9718 48.5383C50.038 50.6034 51.2554 52.5699 52.5959 54.435C52.6519 54.5139 52.7526 54.5477 52.845 54.5195C58.6464 52.7249 64.529 50.0174 70.6019 45.5576C70.6551 45.5182 70.6887 45.459 70.6943 45.3942C72.1747 30.0791 68.2147 16.7757 60.1968 4.9823C60.1772 4.9429 60.1437 4.9147 60.1045 4.8978ZM23.7259 37.3253C20.2276 37.3253 17.3451 34.1136 17.3451 30.1693C17.3451 26.225 20.1717 23.0133 23.7259 23.0133C27.308 23.0133 30.1626 26.2532 30.1066 30.1693C30.1066 34.1136 27.28 37.3253 23.7259 37.3253ZM47.3178 37.3253C43.8196 37.3253 40.9371 34.1136 40.9371 30.1693C40.9371 26.225 43.7636 23.0133 47.3178 23.0133C50.9 23.0133 53.7545 26.2532 53.6986 30.1693C53.6986 34.1136 50.9 37.3253 47.3178 37.3253Z" />
                            </svg>
                        </x-slot>
                    </x-home.responsive-nav-link>
                    @guest
                        <x-home.responsive-nav-link title="{{ __('Log in') }}" url="{{ route('login') }}">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                            </x-slot>
                        </x-home.responsive-nav-link>
                    @else
                        <x-home.responsive-nav-link title="{{ __('Go to Dashboard') }}" url="{{ route('dashboard') }}">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                                </svg>
                            </x-slot>
                        </x-home.responsive-nav-link>
                    @endguest
                    <li class="w-full">
                        <button id="switchTheme" class="flex items-center h-10 px-4 py-3 text-white no-underline transition duration-150 rounded-lg shadow hover:text-gray-50 hover:text-underline bg-gray-900/40 hover:bg-gray-900/50 border-gray-900/50 backdrop-blur-sm hover:translate-x-1 dark:text-yellow-500 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </li>
                    <li class="w-full">
                        <x-jet-dropdown align="left">
                            <x-slot name="trigger">
                                <div class="flex items-center h-10 px-2 text-white no-underline transition duration-150 rounded-lg shadow max-w-max hover:text-gray-50 hover:text-underline bg-gray-900/40 hover:bg-gray-900/50 border-gray-900/50 backdrop-blur-sm hover:translate-x-1 dark:text-yellow-500 focus:outline-none">
                                    @include('vendor.language.flag')
                                </div>
                            </x-slot>
                            <x-slot name="content">
                                @include('vendor.language.flags')
                            </x-slot>
                        </x-jet-dropdown>
                    </li>
                </ul>
            </div>

            <div class="z-20 flex-grow hidden w-full p-4 mt-2 lg:flex lg:items-center lg:w-auto lg:mt-0 lg:p-0"
                id="nav-content">
                <ul class="items-center justify-end flex-1 list-reset lg:flex">
                    <x-home.nav-link title="{{ __('Blog') }}" url="{{ route('blog.index') }}">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </x-slot>
                    </x-home.nav-link>
                    <x-home.nav-link title="Forum" url="https://pokemon3d.net/forum/">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                            </svg>
                        </x-slot>
                    </x-home.nav-link>
                    <x-home.nav-link title="Wiki" url="https://pokemon3d.net/wiki/">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </x-slot>
                    </x-home.nav-link>
                    <x-home.nav-link title="Github" url="https://github.com/P3D-Legacy/P3D-Legacy">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 1792 1792"><path d="M896 128q209 0 385.5 103t279.5 279.5 103 385.5q0 251-146.5 451.5t-378.5 277.5q-27 5-40-7t-13-30q0-3 .5-76.5t.5-134.5q0-97-52-142 57-6 102.5-18t94-39 81-66.5 53-105 20.5-150.5q0-119-79-206 37-91-8-204-28-9-81 11t-92 44l-38 24q-93-26-192-26t-192 26q-16-11-42.5-27t-83.5-38.5-85-13.5q-45 113-8 204-79 87-79 206 0 85 20.5 150t52.5 105 80.5 67 94 39 102.5 18q-39 36-49 103-21 10-45 15t-57 5-65.5-21.5-55.5-62.5q-19-32-48.5-52t-49.5-24l-20-3q-21 0-29 4.5t-5 11.5 9 14 13 12l7 5q22 10 43.5 38t31.5 51l10 23q13 38 44 61.5t67 30 69.5 7 55.5-3.5l23-4q0 38 .5 88.5t.5 54.5q0 18-13 30t-40 7q-232-77-378.5-277.5t-146.5-451.5q0-209 103-385.5t279.5-279.5 385.5-103zm-477 1103q3-7-7-12-10-3-13 2-3 7 7 12 9 6 13-2zm31 34q7-5-2-16-10-9-16-3-7 5 2 16 10 10 16 3zm30 45q9-7 0-19-8-13-17-6-9 5 0 18t17 7zm42 42q8-8-4-19-12-12-20-3-9 8 4 19 12 12 20 3zm57 25q3-11-13-16-15-4-19 7t13 15q15 6 19-6zm63 5q0-13-17-11-16 0-16 11 0 13 17 11 16 0 16-11zm58-10q-2-11-18-9-16 3-14 15t18 8 14-14z"></path></svg>
                        </x-slot>
                    </x-home.nav-link>
                    <x-home.nav-link title="Discord" url="http://www.discord.me/p3d">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 71 55">
                                <path d="M60.1045 4.8978C55.5792 2.8214 50.7265 1.2916 45.6527 0.41542C45.5603 0.39851 45.468 0.440769 45.4204 0.525289C44.7963 1.6353 44.105 3.0834 43.6209 4.2216C38.1637 3.4046 32.7345 3.4046 27.3892 4.2216C26.905 3.0581 26.1886 1.6353 25.5617 0.525289C25.5141 0.443589 25.4218 0.40133 25.3294 0.41542C20.2584 1.2888 15.4057 2.8186 10.8776 4.8978C10.8384 4.9147 10.8048 4.9429 10.7825 4.9795C1.57795 18.7309 -0.943561 32.1443 0.293408 45.3914C0.299005 45.4562 0.335386 45.5182 0.385761 45.5576C6.45866 50.0174 12.3413 52.7249 18.1147 54.5195C18.2071 54.5477 18.305 54.5139 18.3638 54.4378C19.7295 52.5728 20.9469 50.6063 21.9907 48.5383C22.0523 48.4172 21.9935 48.2735 21.8676 48.2256C19.9366 47.4931 18.0979 46.6 16.3292 45.5858C16.1893 45.5041 16.1781 45.304 16.3068 45.2082C16.679 44.9293 17.0513 44.6391 17.4067 44.3461C17.471 44.2926 17.5606 44.2813 17.6362 44.3151C29.2558 49.6202 41.8354 49.6202 53.3179 44.3151C53.3935 44.2785 53.4831 44.2898 53.5502 44.3433C53.9057 44.6363 54.2779 44.9293 54.6529 45.2082C54.7816 45.304 54.7732 45.5041 54.6333 45.5858C52.8646 46.6197 51.0259 47.4931 49.0921 48.2228C48.9662 48.2707 48.9102 48.4172 48.9718 48.5383C50.038 50.6034 51.2554 52.5699 52.5959 54.435C52.6519 54.5139 52.7526 54.5477 52.845 54.5195C58.6464 52.7249 64.529 50.0174 70.6019 45.5576C70.6551 45.5182 70.6887 45.459 70.6943 45.3942C72.1747 30.0791 68.2147 16.7757 60.1968 4.9823C60.1772 4.9429 60.1437 4.9147 60.1045 4.8978ZM23.7259 37.3253C20.2276 37.3253 17.3451 34.1136 17.3451 30.1693C17.3451 26.225 20.1717 23.0133 23.7259 23.0133C27.308 23.0133 30.1626 26.2532 30.1066 30.1693C30.1066 34.1136 27.28 37.3253 23.7259 37.3253ZM47.3178 37.3253C43.8196 37.3253 40.9371 34.1136 40.9371 30.1693C40.9371 26.225 43.7636 23.0133 47.3178 23.0133C50.9 23.0133 53.7545 26.2532 53.6986 30.1693C53.6986 34.1136 50.9 37.3253 47.3178 37.3253Z" />
                            </svg>
                        </x-slot>
                    </x-home.nav-link>
                    @guest
                        <x-home.nav-link title="{{ __('Log in') }}" url="{{ route('login') }}">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                            </x-slot>
                        </x-home.nav-link>
                    @else
                        <x-home.nav-link title="{{ __('Go to Dashboard') }}" url="{{ route('dashboard') }}">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                                </svg>
                            </x-slot>
                        </x-home.nav-link>
                    @endguest
                    {{-- <x-home.nav-link title="Register" url="{{ route('register') }}">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                              </svg>
                        </x-slot>
                    </x-home.nav-link> --}}
                    <button id="switchTheme2" class="flex items-center justify-center w-10 h-10 text-white focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" id="theme-toggle-light-icon">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="hidden w-5 h-5" viewBox="0 0 20 20" fill="currentColor" id="theme-toggle-dark-icon">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                        </svg>
                    </button>
                    <x-jet-dropdown align="right">
                        <x-slot name="trigger">
                            <div class="flex items-center w-10 h-10 text-white max-w-max focus:outline-none">
                                @include('vendor.language.flag')
                            </div>
                        </x-slot>
                        <x-slot name="content">
                            @include('vendor.language.flags')
                        </x-slot>
                    </x-jet-dropdown>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container h-auto mx-auto text-white sm:min-h-screen">
        <div class="px-3 text-center lg:px-0">
            <h1 class="my-4 text-2xl font-black leading-tight md:text-3xl lg:text-5xl">
                @lang('Old school Pokémon in a 3D world!')
            </h1>
            <p class="mb-8 text-base leading-normal text-gray-50 md:text-xl lg:text-2xl">
                @lang('Bringing the games from the early generation of Pokémon games to the modern era.')
            </p>
            <x-download-button />
        </div>

        <div class="z-auto flex items-center content-end w-full mx-auto overflow-hidden lg:w-11/12">
            <div class="flex flex-1 m-6 bg-white rounded-t rounded-b-lg shadow-xl browser-mockup with-url md:m-12 aspect-w-16 aspect-h-9 shadow-black/50">
                <video muted controls class="object-cover object-center w-full h-full rounded-b-lg lg:w-full lg:h-full">
                    <source src="https://files.pokemon3d.net/video/trailer.webm" type="video/webm">
                    @lang('Your browser does not support the video tag.')
                </video>
            </div>
        </div>
    </div>

    <section class="py-12 bg-gray-100 border-b dark:bg-gray-900 dark:border-black">
        <div class="grid items-center justify-between max-w-full grid-flow-row grid-cols-1 gap-4 mx-auto text-4xl font-bold text-gray-900 opacity-75 md:text-5xl sm:grid-flow-auto sm:grid-cols-3 xl:grid-cols-6 dark:text-gray-100">

            <div class="flex flex-col items-center">
                <div class="flex">
                    {{ $averageRating }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-auto h-12 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                </div>
                <div class="flex text-lg text-gray-600 md:text-xl dark:text-gray-300">{{ App\Helpers\NumberHelper::nearestK($numberOfReviews) }} @lang('User Reviews')</div>
                {{-- USER REVIEWS OF THE GAME --}}
            </div>

            <div class="flex flex-col items-center">
                <div class="flex">{{ ucfirst(\App\Helpers\StatsHelper::getInGameSeason()) }}</div>
                <div class="flex text-lg text-gray-600 md:text-xl dark:text-gray-300">@lang('In-Game Season')</div>
                {{-- CURRENT IN-GAME SEASON --}}
            </div>

            <div class="flex flex-col items-center">
                <div class="flex">{{ App\Helpers\NumberHelper::nearestK(\App\Helpers\StatsHelper::countPlayers()) }}</div>
                <div class="flex text-lg text-gray-600 md:text-xl dark:text-gray-300">@lang('Online Players')</div>
                {{-- ONLINE USERS FROM THE SERVER --}}
            </div>

            <div class="flex flex-col items-center">
                <div class="flex">{{ App\Helpers\NumberHelper::nearestK(App\Models\User::count()) }}</div>
                <div class="flex text-lg text-gray-600 md:text-xl dark:text-gray-300">@lang('Active Users')</div>
                {{-- ACTIVE USERS FROM THE WEBSITE --}}
            </div>

            <div class="flex flex-col items-center">
                <div class="flex">{{ App\Helpers\NumberHelper::nearestK(\App\Helpers\DiscordHelper::countMembers()) }}</div>
                <div class="flex text-lg text-gray-600 md:text-xl dark:text-gray-300">@lang('Discord Users')</div>
                {{-- TOTAL COUNT DISCORD USERS --}}
            </div>

            <div class="flex flex-col items-center">
                <div class="flex">{{ App\Helpers\NumberHelper::nearestK(\App\Helpers\StatsHelper::countForumMembers()) }}</div>
                <div class="flex text-lg text-gray-600 md:text-xl dark:text-gray-300">@lang('Forum Users')</div>
                {{-- ACTIVE USERS FROM THE FORUM --}}
            </div>

        </div>
    </section>

    <section class="py-8 bg-white border-b dark:bg-gray-800 dark:border-black">
        <div class="container flex flex-wrap pt-4 pb-12 mx-auto">
            <h2 class="w-full my-2 text-2xl font-black leading-tight text-center text-gray-800 uppercase sm:text-3xl dark:text-gray-200">
                @lang('Latest news')
            </h2>
            <div class="w-full mb-4">
                <div class="w-64 h-1 py-0 mx-auto my-0 bg-black rounded-t opacity-25 dark:bg-white"></div>
            </div>
            @forelse ($posts as $post)
                <x-home.article :post="$post" />
            @empty
                <div class="w-full text-xs text-center">
                    <p class="mb-1 dark:text-gray-400">There is nothing to show...</p>
                    <a href="{{ route('blog.index') }}" class="text-green-500 hover:underline">
                        @lang('Go to blog')
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            @endforelse
            @if($posts->count() > 0)
                <div class="w-full text-xs text-center">
                    <a href="{{ route('blog.index') }}" class="text-green-500 hover:underline">
                        @lang('Read more blog posts')
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </section>

    <section class="px-4 py-8 sm:p-8">
        <div class="container max-w-5xl m-8 mx-auto text-white">
            <h2 class="w-full my-2 text-2xl font-black leading-tight text-center uppercase sm:text-3xl">
                @lang('Screenshots')
            </h2>
            <div class="w-full mb-4">
                <div class="w-64 h-1 py-0 mx-auto my-0 bg-white rounded-t opacity-25"></div>
            </div>
        
            <div id="carousel" class="relative carousel slide carousel-fade dark:carousel-dark" data-bs-ride="carousel">
                <div class="relative w-full overflow-hidden shadow-xl rounded-xl shadow-black/50 carousel-inner">
                    @php
                        $screenshots = [
                            0 => [
                                'title' => 'Elms Lab',
                                'path' => 'img/carousel/Elms_Lab.png',
                                'author' => 'JappaWakka',
                            ],
                            1 => [
                                'title' => 'Player House Bedroom',
                                'path' => 'img/carousel/Player_House_Bedroom.png',
                                'author' => 'JappaWakka',
                            ],
                            2 => [
                                'title' => 'Player House Bedroom',
                                'path' => 'img/carousel/Player_House_Bedroom2.png',
                                'author' => 'JappaWakka',
                            ],
                            3 => [
                                'title' => 'Ferry',
                                'path' => 'img/carousel/Ferry.png',
                                'author' => 'GhostlyRose',
                            ],
                            4 => [
                                'title' => 'PokeCenter',
                                'path' => 'img/carousel/PokeCenter.png',
                                'author' => 'GhostlyRose',
                            ],
                            5 => [
                                'title' => 'New Bark Town',
                                'path' => 'img/carousel/nbt.png',
                                'author' => 'JappaWakka',
                            ],
                            6 => [
                                'title' => 'Cerulean Cave',
                                'path' => 'img/carousel/cerulean_cave.png',
                                'author' => 'JappaWakka',
                            ],
                        ]
                    @endphp
                    @foreach ($screenshots as $screenshot)
                        <x-home.screenshot title="{{ $screenshot['title'] }}" path="{{ $screenshot['path'] }}" author="{{ $screenshot['author'] }}" active="{{ $loop->first ?? true }}" />
                    @endforeach
                </div>
                <button class="absolute top-0 bottom-0 left-0 flex items-center justify-center p-0 text-center border-0 carousel-control-prev hover:outline-none hover:no-underline focus:outline-none focus:no-underline" type="button" data-bs-target="#carousel" data-bs-slide="prev">
                    <span class="inline-block w-12 h-12 bg-no-repeat carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="absolute top-0 bottom-0 right-0 flex items-center justify-center p-0 text-center border-0 carousel-control-next hover:outline-none hover:no-underline focus:outline-none focus:no-underline" type="button" data-bs-target="#carousel" data-bs-slide="next">
                    <span class="inline-block w-12 h-12 bg-no-repeat carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

        </div>
    </section>

    <section class="p-4 border-b sm:p-8 dark:border-black bg-black/20">
        <div class="container max-w-5xl m-8 mx-auto text-white">
            <h2 class="w-full my-2 text-2xl font-black leading-tight text-center uppercase sm:text-3xl">
                @lang('History')
            </h2>
            <div class="w-full mb-4">
                <div class="w-64 h-1 py-0 mx-auto my-0 bg-white rounded-t opacity-25"></div>
            </div>

            <div class="flex flex-wrap">
                <p class="mb-8">
                    @lang(':game is a video game originally created by :author. It is heavily inspired by Minecraft, and the Pokémon series.', [
                        'game' => config('app.name'),
                        'author' => '<a href="https://github.com/nilllzz" class="text-green-100 hover:underline hover:text-white">Nilllzz</a>',
                    ])
                    @lang(':game focuses on the strong points of Pokémon Gold and Silver versions and their remakes, and gives players a taste as to how the once 2D world they knew was in 3D. They could even see through the eyes of their trainer.', [
                        'game' => config('app.name'),
                    ])
                </p>
            </div>
        </div>
    </section>

    <section class="py-8 bg-white border-b dark:bg-gray-800 dark:border-black">
        <div class="container max-w-5xl m-8 mx-auto">
            <h2 class="w-full my-2 text-2xl font-black leading-tight text-center text-gray-800 uppercase sm:text-3xl dark:text-gray-200">
                @lang('Features')
            </h2>
            <div class="w-full mb-4">
                <div class="w-64 h-1 py-0 mx-auto my-0 bg-black rounded-t opacity-25 dark:bg-white"></div>
            </div>

            <div class="flex flex-wrap">
                <div class="w-5/6 p-6 sm:w-1/2">
                    <h3 class="mb-3 text-xl font-bold leading-none text-gray-800 sm:text-3xl dark:text-gray-200">
                        @lang('Nostalgia')
                    </h3>
                    <p class="text-gray-600 sm:mb-8 dark:text-gray-400">
                        @lang('Remember the old days when you were playing on a GameBoy? If so; you should try out this game and awake your inner child.')
                    </p>
                </div>
                <div class="w-full px-4 sm:p-6 sm:w-1/2">
                    <img src="{{ asset('img/pikachu.png') }}" />
                </div>
            </div>

            <div class="flex flex-col-reverse flex-wrap sm:flex-row">
                <div class="grid w-full px-4 sm:p-6 sm:mt-6 sm:w-1/2 justify-items-end">
                    <img src="{{ asset('img/rhydon.png') }}" />
                </div>
                <div class="w-full p-6 mt-6 sm:w-1/2">
                    <div class="align-middle">
                        <h3 class="mb-3 text-xl font-bold leading-none text-gray-800 sm:text-3xl dark:text-gray-200">
                            @lang('Most Generations and Regions')
                        </h3>
                        <p class="mb-8 text-gray-600 dark:text-gray-400">
                            @lang(':game will have support for all generations of Pokémon in the future and all regions will be accessible in the game.', [
                                'game' => config('app.name'),
                            ])
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap">
                <div class="w-5/6 p-6 sm:w-1/2">
                    <h3 class="mb-3 text-xl font-bold leading-none text-gray-800 sm:text-3xl dark:text-gray-200">
                        @lang('A New Experience')
                    </h3>
                    <p class="text-gray-600 sm:mb-8 dark:text-gray-400">
                        @lang(':game focuses on the strong points of Pokémon Gold and Silver versions and their remakes, and gives players a taste as to how the once 2D world they knew was in 3D. They could even see through the eyes of their trainer.', [
                            'game' => config('app.name'),
                        ])
                    </p>
                </div>
                <div class="w-full px-4 sm:p-6 sm:w-1/2">
                    <img src="{{ asset('img/scizor.png') }}" />
                </div>
            </div>
        </div>
    </section>

    <section class="py-8 sm:py-12">
        <div class="container flex flex-wrap items-center justify-between pb-8 mx-auto text-white sm:pb-12">
            <h2 class="w-full my-2 text-2xl font-black leading-tight text-center uppercase sm:text-3xl lg:mt-8">
                @lang('Media')
            </h2>
            <div class="w-full mb-4">
                <div class="w-64 h-1 py-0 mx-auto my-0 bg-white rounded-t opacity-25"></div>
            </div>
            
            <div class="flex flex-wrap items-center w-full gap-4 p-4 mb-8 sm:p-8 md:mb-0 flex-between">
                <x-home.media-article title="Pokemon 3D creator envisions a fully cooperative Pokemon campaign" url="https://www.polygon.com/2012/12/7/3740086/pokemon-3d-interview" author="Polygon" date="Dec 7, 2012, 4:40pm EST" />
                <x-home.media-article title="This Fan-Made Pokémon Remake Is In 3D And First Person" url="https://www.kotaku.com.au/2012/12/this-fan-made-pokemon-remake-is-in-3d-and-first-person/" author="Kotaku" date="December 4, 2012 at 7:00 pm" />
                <x-home.media-article title="'Pokemon' gets a virtual reality makeover for Oculus Rift" url="https://www.theverge.com/2014/2/25/5445930/pokemon-3d-oculus-rift" author="The Verge" date="Feb 25, 2014, 11:54am EST" />
            </div>

        </div>
    </section>

    <section class="w-full px-3 py-12 mx-auto text-center text-white bg-black/20">
        <h2 class="w-full my-2 text-2xl font-black leading-tight text-center uppercase sm:text-3xl">
            @lang('Miss your childhood?')
        </h2>
        <div class="w-full mb-4">
            <div class="w-1/6 h-1 py-0 mx-auto my-0 bg-white rounded-t opacity-25"></div>
        </div>

        <h3 class="my-4 text-xl font-extrabold text-gray-100 sm:text-2xl">
            @lang('Go back in time with :game!', [
                'game' => config('app.name')
            ])
        </h3>

        <x-download-button />
    </section>

    <!--Footer-->
    <footer class="bg-white dark:bg-gray-800">
        <div class="container px-8 mx-auto mt-8">
            <div class="flex flex-col w-full py-6 md:flex-row">
                <div class="px-3 mb-6 flex-2">
                    <x-logo-large class="max-w-xs" />
                    <p class="mt-3 text-sm text-gray-600 dark:text-gray-200">
                        @lang(':game is not affiliated with', ['game' => config('app.name')]) Nintendo, Creatures Inc. or GAME FREAK Inc.
                    </p>
                    <p class="mt-3 text-sm text-gray-500 dark:text-gray-300">
                        pokemon3d.net @lang('is owned and operated by') <a href="https://kilobyte.no/" class="text-green-600 no-underline hover:underline">Kilobyte AS</a>
                    </p>
                    <p class="mt-3 text-sm text-gray-400 dark:text-gray-400">
                        @lang('This website is open-source on :github, thanks to our contributors! :hearts', [
                            'github' => '<a href="https://github.com/P3D-Legacy/pokemon3d.net" class="hover:underline"><svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="inline-block w-4 h-4" viewBox="0 0 1792 1792"><path d="M896 128q209 0 385.5 103t279.5 279.5 103 385.5q0 251-146.5 451.5t-378.5 277.5q-27 5-40-7t-13-30q0-3 .5-76.5t.5-134.5q0-97-52-142 57-6 102.5-18t94-39 81-66.5 53-105 20.5-150.5q0-119-79-206 37-91-8-204-28-9-81 11t-92 44l-38 24q-93-26-192-26t-192 26q-16-11-42.5-27t-83.5-38.5-85-13.5q-45 113-8 204-79 87-79 206 0 85 20.5 150t52.5 105 80.5 67 94 39 102.5 18q-39 36-49 103-21 10-45 15t-57 5-65.5-21.5-55.5-62.5q-19-32-48.5-52t-49.5-24l-20-3q-21 0-29 4.5t-5 11.5 9 14 13 12l7 5q22 10 43.5 38t31.5 51l10 23q13 38 44 61.5t67 30 69.5 7 55.5-3.5l23-4q0 38 .5 88.5t.5 54.5q0 18-13 30t-40 7q-232-77-378.5-277.5t-146.5-451.5q0-209 103-385.5t279.5-279.5 385.5-103zm-477 1103q3-7-7-12-10-3-13 2-3 7 7 12 9 6 13-2zm31 34q7-5-2-16-10-9-16-3-7 5 2 16 10 10 16 3zm30 45q9-7 0-19-8-13-17-6-9 5 0 18t17 7zm42 42q8-8-4-19-12-12-20-3-9 8 4 19 12 12 20 3zm57 25q3-11-13-16-15-4-19 7t13 15q15 6 19-6zm63 5q0-13-17-11-16 0-16 11 0 13 17 11 16 0 16-11zm58-10q-2-11-18-9-16 3-14 15t18 8 14-14z"></path></svg> Github</a>', 'hearts' => '<span class="text-red-400 dark:text-red-700">&hearts;</span>'])
                    </p>
                    <p class="mt-3 text-xs text-gray-300 dark:text-gray-500"><a class="hover:underline" href="https://github.com/P3D-Legacy/pokemon3d.net/blob/main/CHANGELOG.md">{{ setting('APP_VERSION') ?? 'N/A' }}</a></p>
                </div>
                <div class="flex-1 px-3">
                    <p class="font-extrabold text-gray-500 uppercase dark:text-gray-200 md:mb-6">@lang('Legal')</p>
                    <ul class="mb-6 list-reset">
                        <x-home.footer-link title="{{ __('Terms and Conditions') }}" url="{{ route('terms.show') }}" />
                        <x-home.footer-link title="{{ __('Privacy Policy') }}" url="{{ route('policy.show') }}" />
                    </ul>
                </div>
                <div class="flex-1 px-3">
                    <p class="font-extrabold text-gray-500 uppercase dark:text-gray-200 md:mb-6">@lang('Social')</p>
                    <ul class="mb-6 list-reset">
                        <x-home.footer-link title="Discord" url="{{ route('discord') }}" />
                        <x-home.footer-link title="Github" url="{{ route('github') }}" />
                    </ul>
                </div>
                <div class="flex-1 px-3">
                    <p class="font-extrabold text-gray-500 uppercase dark:text-gray-200 md:mb-6">
                        {{ config('app.name') }}
                    </p>
                    <ul class="mb-6 list-reset">
                        <x-home.footer-link title="{{ __('Official Blog') }}" url="{{ route('blog.index') }}" />
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</x-guest-layout>
