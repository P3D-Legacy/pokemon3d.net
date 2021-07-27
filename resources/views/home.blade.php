<x-guest-layout>
    <!--Nav-->
    <nav id="header" class="w-full z-30 top-0 text-white py-1 lg:py-6">
        <div class="w-full container mx-auto flex flex-wrap items-center justify-between mt-0 px-2 py-2 lg:py-6">
            <div class="pl-4 flex items-center">
                <a class="text-white no-underline hover:no-underline font-bold text-2xl lg:text-4xl font-mono" href="#">
                    <x-logo-small class="" />
                </a>
            </div>

            <div class="block lg:hidden pr-4">
                <button id="nav-toggle"
                    class="flex items-center px-2 py-1 border rounded text-white border-white hover:text-gray-200 hover:border-gray-200 appearance-none focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <div class="w-full flex-grow lg:flex lg:items-center lg:w-auto hidden mt-2 lg:mt-0 p-4 lg:p-0 z-20"
                id="nav-content">
                <ul class="list-reset lg:flex justify-end flex-1 items-center">
                   <x-home.nav-link title="Forum" url="#">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                            </svg>
                        </x-slot>
                    </x-home.nav-link>
                    <x-home.nav-link title="Wiki" url="#">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </x-slot>
                    </x-home.nav-link>
                    <x-home.nav-link title="Github" url="#">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 1792 1792"><path d="M896 128q209 0 385.5 103t279.5 279.5 103 385.5q0 251-146.5 451.5t-378.5 277.5q-27 5-40-7t-13-30q0-3 .5-76.5t.5-134.5q0-97-52-142 57-6 102.5-18t94-39 81-66.5 53-105 20.5-150.5q0-119-79-206 37-91-8-204-28-9-81 11t-92 44l-38 24q-93-26-192-26t-192 26q-16-11-42.5-27t-83.5-38.5-85-13.5q-45 113-8 204-79 87-79 206 0 85 20.5 150t52.5 105 80.5 67 94 39 102.5 18q-39 36-49 103-21 10-45 15t-57 5-65.5-21.5-55.5-62.5q-19-32-48.5-52t-49.5-24l-20-3q-21 0-29 4.5t-5 11.5 9 14 13 12l7 5q22 10 43.5 38t31.5 51l10 23q13 38 44 61.5t67 30 69.5 7 55.5-3.5l23-4q0 38 .5 88.5t.5 54.5q0 18-13 30t-40 7q-232-77-378.5-277.5t-146.5-451.5q0-209 103-385.5t279.5-279.5 385.5-103zm-477 1103q3-7-7-12-10-3-13 2-3 7 7 12 9 6 13-2zm31 34q7-5-2-16-10-9-16-3-7 5 2 16 10 10 16 3zm30 45q9-7 0-19-8-13-17-6-9 5 0 18t17 7zm42 42q8-8-4-19-12-12-20-3-9 8 4 19 12 12 20 3zm57 25q3-11-13-16-15-4-19 7t13 15q15 6 19-6zm63 5q0-13-17-11-16 0-16 11 0 13 17 11 16 0 16-11zm58-10q-2-11-18-9-16 3-14 15t18 8 14-14z"></path></svg>
                        </x-slot>
                    </x-home.nav-link>
                    <x-home.nav-link title="Discord" url="#">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 71 55">
                                <path d="M60.1045 4.8978C55.5792 2.8214 50.7265 1.2916 45.6527 0.41542C45.5603 0.39851 45.468 0.440769 45.4204 0.525289C44.7963 1.6353 44.105 3.0834 43.6209 4.2216C38.1637 3.4046 32.7345 3.4046 27.3892 4.2216C26.905 3.0581 26.1886 1.6353 25.5617 0.525289C25.5141 0.443589 25.4218 0.40133 25.3294 0.41542C20.2584 1.2888 15.4057 2.8186 10.8776 4.8978C10.8384 4.9147 10.8048 4.9429 10.7825 4.9795C1.57795 18.7309 -0.943561 32.1443 0.293408 45.3914C0.299005 45.4562 0.335386 45.5182 0.385761 45.5576C6.45866 50.0174 12.3413 52.7249 18.1147 54.5195C18.2071 54.5477 18.305 54.5139 18.3638 54.4378C19.7295 52.5728 20.9469 50.6063 21.9907 48.5383C22.0523 48.4172 21.9935 48.2735 21.8676 48.2256C19.9366 47.4931 18.0979 46.6 16.3292 45.5858C16.1893 45.5041 16.1781 45.304 16.3068 45.2082C16.679 44.9293 17.0513 44.6391 17.4067 44.3461C17.471 44.2926 17.5606 44.2813 17.6362 44.3151C29.2558 49.6202 41.8354 49.6202 53.3179 44.3151C53.3935 44.2785 53.4831 44.2898 53.5502 44.3433C53.9057 44.6363 54.2779 44.9293 54.6529 45.2082C54.7816 45.304 54.7732 45.5041 54.6333 45.5858C52.8646 46.6197 51.0259 47.4931 49.0921 48.2228C48.9662 48.2707 48.9102 48.4172 48.9718 48.5383C50.038 50.6034 51.2554 52.5699 52.5959 54.435C52.6519 54.5139 52.7526 54.5477 52.845 54.5195C58.6464 52.7249 64.529 50.0174 70.6019 45.5576C70.6551 45.5182 70.6887 45.459 70.6943 45.3942C72.1747 30.0791 68.2147 16.7757 60.1968 4.9823C60.1772 4.9429 60.1437 4.9147 60.1045 4.8978ZM23.7259 37.3253C20.2276 37.3253 17.3451 34.1136 17.3451 30.1693C17.3451 26.225 20.1717 23.0133 23.7259 23.0133C27.308 23.0133 30.1626 26.2532 30.1066 30.1693C30.1066 34.1136 27.28 37.3253 23.7259 37.3253ZM47.3178 37.3253C43.8196 37.3253 40.9371 34.1136 40.9371 30.1693C40.9371 26.225 43.7636 23.0133 47.3178 23.0133C50.9 23.0133 53.7545 26.2532 53.6986 30.1693C53.6986 34.1136 50.9 37.3253 47.3178 37.3253Z" />
                            </svg>
                        </x-slot>
                    </x-home.nav-link>
                    <x-home.nav-link title="Login" url="#">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                        </x-slot>
                    </x-home.nav-link>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mx-auto min-h-screen h-auto text-white">
        <div class="text-center px-3 lg:px-0">
            <h1 class="my-4 text-2xl md:text-3xl lg:text-5xl font-black leading-tight">
                Oldschool Pokémon in a 3D world!
            </h1>
            <p class="leading-normal text-gray-50 text-base md:text-xl lg:text-2xl mb-8">
                Bringing the games from the early generation of Pokémon games to the modern era.
            </p>
            <div class="inline-block mt-2 md:mt-6">
                <button class="mx-auto lg:mx-0 font-extrabold rounded py-4 px-8 shadow-xl w-76 inline-flex items-center justify-center transform group-hover:-translate-y-0.5 transition-all duration-150 text-green-50 group-hover:text-green-100 bg-green-500 hover:bg-green-600 border border-green-400 hover:border-green-500">
                    {{--
                        WINDOWS ICON
                        
                        <svg version="1.2" baseProfile="tiny-ps" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-opacity-50 transform" viewBox="0 0 58 58" width="58" height="58" fill="currentColor" stroke="none"><path class="shp0" d="M0 14L0 28L28 28L28 0L0 0L0 14ZM30 14L30 28L58 28L58 0L30 0L30 14ZM0 44L0 58L28 58L28 30L0 30L0 44ZM30 44L30 58L58 58L58 30L30 30L30 44Z" /></svg>
                    --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6 mr-3 text-opacity-50 transform">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Download {{ GitHubHelper::getVersion() }}<sup>&dagger;</sup></span>
                </button>
                <div class="text-xs mt-1 mb-2 text-gray-100">
                    <span>Released {{ \Carbon\Carbon::parse(GitHubHelper::getReleaseDate())->diffForHumans() }}</span>
                    <span class="px-2">&mdash;</span>
                    <span><a href="https://pokemon3d.net/wiki/index.php/Pok%C3%A9mon_3D#Requirements"><sup>&dagger;</sup> View Requirements</a></span>
                </div>
            </div>
        </div>

        <div class="flex items-center mx-auto content-end z-auto overflow-hidden w-full">
            <div class="browser-mockup flex flex-1 m-6 md:px-0 md:m-12 bg-white rounded-lg shadow-xl aspect-w-16 aspect-h-9">
                <img src="{{ asset('img/daniel_ingame.png') }}" class="w-full h-full object-center object-cover lg:w-full lg:h-full" />
            </div>
        </div>
    </div>

    <section class="bg-gray-100 border-b py-12 mt-24 sm:mt-16 xl:mt-0">
        <div class="container mx-auto flex flex-wrap items-center justify-between">
            <div class="flex flex-1 flex-wrap max-w-4xl mx-auto items-center justify-between text-5xl text-gray-900 font-bold opacity-75">
                <div class="w-1/2 p-4 md:w-auto flex flex-col items-center">
                    <div class="flex">0</div>
                    <div class="flex text-xl text-gray-600">Online Users</div>
                </div>

                <div class="w-1/2 p-4 md:w-auto flex flex-col items-center">
                    <div class="flex">0</div>
                    <div class="flex text-xl text-gray-600">Active Users</div>
                </div>

                <div class="w-1/2 p-4 md:w-auto flex flex-col items-center">
                    <div class="flex">0</div>
                    <div class="flex text-xl text-gray-600">Discord Users</div>
                </div>

                <div class="w-1/2 p-4 md:w-auto flex flex-col items-center">
                    <div class="flex">0</div>
                    <div class="flex text-xl text-gray-600">Forum Users</div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white border-b py-8">
        <div class="container mx-auto flex flex-wrap pt-4 pb-12">
            <h2 class="w-full my-2 text-3xl font-black leading-tight text-center text-gray-800 uppercase">
                Latest news
            </h2>
            <div class="w-full mb-4">
                <div class="h-1 mx-auto bg-black w-64 opacity-25 my-0 py-0 rounded-t"></div>
            </div>

            <x-home.article />
            <x-home.article />
            <x-home.article />

        </div>
    </section>

    <section class="bg-transparent border-b p-8">
        <div class="container max-w-5xl mx-auto m-8 text-white">
            <h2 class="w-full my-2 text-3xl font-black leading-tight text-center uppercase">
                History
            </h2>
            <div class="w-full mb-4">
                <div class="h-1 mx-auto bg-white w-64 opacity-25 my-0 py-0 rounded-t"></div>
            </div>

            <div class="flex flex-wrap">
                <p class="mb-8">
                    {{ config('app.name') }} is a video game originally created by <a href="https://github.com/nilllzz" class="text-green-50 hover:underline">Nilllzz</a>. It is heavily inspired by Minecraft, and the Pokémon series. {{ config('app.name') }} focused on the strong points of Pokémon Gold and Silver versions and their remakes, and gives players a taste as to how the once 2D world they knew was in 3D. They could even see through the eyes of their own trainer.
                </p>
            </div>
        </div>
    </section>

    <section class="bg-white border-b py-8">
        <div class="container max-w-5xl mx-auto m-8">
            <h2 class="w-full my-2 text-3xl font-black leading-tight text-center text-gray-800 uppercase">
                Features
            </h2>
            <div class="w-full mb-4">
                <div class="h-1 mx-auto bg-black w-64 opacity-25 my-0 py-0 rounded-t"></div>
            </div>

            <div class="flex flex-wrap">
                <div class="w-5/6 sm:w-1/2 p-6">
                    <h3 class="text-3xl text-gray-800 font-bold leading-none mb-3">
                        Nostalgia
                    </h3>
                    <p class="text-gray-600 mb-8">
                        Remember the old days when you where playing on a GameBoy? If so; you should try out this game and get the nostalgic feeling as well as visit your inner child.
                    </p>
                </div>
                <div class="w-full sm:w-1/2 p-6">
                    <img src="{{ asset('img/pikachu.png') }}" />
                </div>
            </div>

            <div class="flex flex-wrap flex-col-reverse sm:flex-row">
                <div class="w-full sm:w-1/2 p-6 mt-6 grid justify-items-end">
                    <img src="{{ asset('img/rhydon.png') }}" />
                </div>
                <div class="w-full sm:w-1/2 p-6 mt-6">
                    <div class="align-middle">
                        <h3 class="text-3xl text-gray-800 font-bold leading-none mb-3">
                            Most Generations and Regions
                        </h3>
                        <p class="text-gray-600 mb-8">
                            {{ config('app.name') }} will in the future have support for all generations of pokémon. And all the regions will accessible in the game.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap">
                <div class="w-5/6 sm:w-1/2 p-6">
                    <h3 class="text-3xl text-gray-800 font-bold leading-none mb-3">
                        A New Experience
                    </h3>
                    <p class="text-gray-600 mb-8">
                        {{ config('app.name') }} focused on the strong points of Pokémon Gold and Silver versions and their remakes, and gives players a taste as to how the once 2D world they knew was in 3D.
                    </p>
                </div>
                <div class="w-full sm:w-1/2 p-6">
                    <img src="{{ asset('img/scizor.png') }}" />
                </div>
            </div>
        </div>
    </section>

    <section class="bg-gray-100 border-b py-12 ">
        <div class="container mx-auto flex flex-wrap items-center justify-between pb-12">
            <h2 class="w-full my-2 text-3xl font-black leading-tight text-center text-gray-800 lg:mt-8 uppercase">
                Featured in
            </h2>
            <div class="w-full mb-4">
                <div class="h-1 mx-auto bg-black w-64 opacity-25 my-0 py-0 rounded-t"></div>
            </div>

            <div class="flex flex-1 flex-wrap max-w-4xl mx-auto items-center justify-between text-4xl text-gray-700 font-bold opacity-75">
                <span class="w-full p-4 sm:w-auto flex items-center">
                    Polygon
                </span>

                <span class="w-full p-4 sm:w-auto flex items-center">
                    Kotaku
                </span>

                <span class="w-full p-4 sm:w-auto flex items-center">
                    The Verge
                </span>

                <span class="w-full p-4 sm:w-auto flex items-center">
                    PCMag
                </span>
            </div>
        </div>
    </section>

    <section class="w-full mx-auto text-center py-12 text-white">
        <h2 class="w-full my-2 text-3xl font-black leading-tight text-center uppercase">
            Miss your childhood?
        </h2>
        <div class="w-full mb-4">
            <div class="h-1 mx-auto bg-white w-1/6 opacity-25 my-0 py-0 rounded-t"></div>
        </div>

        <h3 class="my-4 text-2xl font-extrabold text-gray-100">
            Go back in time with {{ config('app.name') }}!
        </h3>

        <button
            class="mt-2 mx-auto font-extrabold rounded py-4 px-8 shadow-xl w-76 inline-flex items-center justify-center transform group-hover:-translate-y-0.5 transition-all duration-150 text-green-50 group-hover:text-green-100 bg-green-500 hover:bg-green-600 border border-green-400 hover:border-green-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-opacity-50 transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
            <span>Download {{ GitHubHelper::getVersion() }}<sup>&dagger;</sup></span>
        </button>
        <a href="https://pokemon3d.net/wiki/index.php/Pok%C3%A9mon_3D#Requirements" class="block mx-auto hover:underline bg-transparent text-gray-100 font-extrabold my-1 md:my-3 py-2 lg:py-4 px-8 text-sm"><sup>&dagger;</sup> View Requirements</a>
    </section>

    <!--Footer-->
    <footer class="bg-white">
        <div class="container mx-auto mt-8 px-8">
            <div class="w-full flex flex-col md:flex-row py-6">
                <div class="flex-2 mb-6 px-3">
                    <a href="#">
                        <x-logo-large class="max-w-xs" />
                    </a>
                    <p class="text-gray-600 text-sm mt-3">
                        {{ config('app.name') }} is not affiliated with Nintendo, Creatures Inc. or GAME FREAK Inc.
                    </p>
                    <p class="text-gray-500 text-sm mt-3">
                        pokemon3d.net is owned and operated by <a href="https://infihex.com/" class="no-underline hover:underline text-green-600">Infihex</a>
                    </p>
                    <p class="text-gray-400 text-sm mt-3">
                        This website is open-source on <a href="https://github.com/P3D-Legacy/pokemon3d.net" class="hover:underline"><svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="h-4 w-4 inline-block" viewBox="0 0 1792 1792"><path d="M896 128q209 0 385.5 103t279.5 279.5 103 385.5q0 251-146.5 451.5t-378.5 277.5q-27 5-40-7t-13-30q0-3 .5-76.5t.5-134.5q0-97-52-142 57-6 102.5-18t94-39 81-66.5 53-105 20.5-150.5q0-119-79-206 37-91-8-204-28-9-81 11t-92 44l-38 24q-93-26-192-26t-192 26q-16-11-42.5-27t-83.5-38.5-85-13.5q-45 113-8 204-79 87-79 206 0 85 20.5 150t52.5 105 80.5 67 94 39 102.5 18q-39 36-49 103-21 10-45 15t-57 5-65.5-21.5-55.5-62.5q-19-32-48.5-52t-49.5-24l-20-3q-21 0-29 4.5t-5 11.5 9 14 13 12l7 5q22 10 43.5 38t31.5 51l10 23q13 38 44 61.5t67 30 69.5 7 55.5-3.5l23-4q0 38 .5 88.5t.5 54.5q0 18-13 30t-40 7q-232-77-378.5-277.5t-146.5-451.5q0-209 103-385.5t279.5-279.5 385.5-103zm-477 1103q3-7-7-12-10-3-13 2-3 7 7 12 9 6 13-2zm31 34q7-5-2-16-10-9-16-3-7 5 2 16 10 10 16 3zm30 45q9-7 0-19-8-13-17-6-9 5 0 18t17 7zm42 42q8-8-4-19-12-12-20-3-9 8 4 19 12 12 20 3zm57 25q3-11-13-16-15-4-19 7t13 15q15 6 19-6zm63 5q0-13-17-11-16 0-16 11 0 13 17 11 16 0 16-11zm58-10q-2-11-18-9-16 3-14 15t18 8 14-14z"></path></svg> Github</a>, thanks to our contributors! &hearts;
                    </p>
                </div>

                <div class="flex-1 px-3">
                    <p class="uppercase font-extrabold text-gray-500 md:mb-6">Information</p>
                    <ul class="list-reset mb-6">
                        <x-home.footer-link title="FAQ" url="#" />
                        <x-home.footer-link title="Help" url="#" />
                        <x-home.footer-link title="Support" url="#" />
                    </ul>
                </div>
                <div class="flex-1 px-3">
                    <p class="uppercase font-extrabold text-gray-500 md:mb-6">Legal</p>
                    <ul class="list-reset mb-6">
                        <x-home.footer-link title="Terms" url="#" />
                        <x-home.footer-link title="Privacy" url="#" />
                    </ul>
                </div>
                <div class="flex-1 px-3">
                    <p class="uppercase font-extrabold text-gray-500 md:mb-6">Social</p>
                    <ul class="list-reset mb-6">
                        <x-home.footer-link title="Facebook" url="#" />
                        <x-home.footer-link title="Discord" url="#" />
                        <x-home.footer-link title="Github" url="#" />
                    </ul>
                </div>
                <div class="flex-1 px-3">
                    <p class="uppercase font-extrabold text-gray-500 md:mb-6">
                        {{ config('app.name') }}
                    </p>
                    <ul class="list-reset mb-6">
                        <x-home.footer-link title="Official Blog" url="#" />
                        <x-home.footer-link title="About Us" url="#" />
                        <x-home.footer-link title="Contact" url="#" />
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</x-guest-layout>
