<x-guest-layout>

    <x-home.nav-menu />

    <div class="container h-auto mx-auto text-white">
        <div class="px-3 text-center lg:px-0">
            <h1 class="my-4 text-2xl font-black leading-tight md:text-3xl lg:text-5xl">
                @lang('Old school Pokémon in a 3D world!')
            </h1>
            <p class="mb-8 text-base leading-normal text-slate-50 md:text-xl lg:text-2xl">
                @lang('Bringing the games from the early generation of Pokémon games to the modern era.')
            </p>
            <x-download-button />
        </div>

        <div class="z-auto flex items-center content-end w-full mx-auto overflow-hidden lg:w-11/12">
            <div class="flex flex-1 mx-6 my-8 bg-white rounded-t rounded-b-lg shadow-xl browser-mockup with-url md:m-12 aspect-w-16 aspect-h-9 shadow-black/50">
                <video muted controls class="object-cover object-center w-full h-full rounded-b-lg lg:w-full lg:h-full">
                    <source src="https://files.pokemon3d.net/video/trailer.mp4" type="video/mp4">
                    @lang('Your browser does not support the video tag.')
                </video>
            </div>
        </div>
    </div>

    <section class="py-12 bg-slate-100 border-b dark:bg-slate-900 dark:border-black">
        <div class="grid items-center justify-between max-w-full grid-flow-row grid-cols-1 gap-4 mx-auto text-4xl font-bold text-slate-900 opacity-75 md:text-5xl sm:grid-flow-auto sm:grid-cols-3 xl:grid-cols-6 dark:text-slate-100">
            <livewire:home.stat-reviews />
            <livewire:home.stat-season />
            <livewire:home.stat-online-players />
            <livewire:home.stat-users />
            <livewire:home.stat-discord-users />
            <livewire:home.stat-forum-users />
        </div>
    </section>

    <section class="py-8 bg-white border-b dark:bg-slate-800 dark:border-black">
        <div class="container flex flex-wrap pt-4 pb-12 mx-auto">
            <h2 class="w-full my-2 text-2xl font-black leading-tight text-center text-slate-800 uppercase sm:text-3xl dark:text-slate-200">
                @lang('Latest news')
            </h2>
            <div class="w-full mb-4">
                <div class="w-64 h-1 py-0 mx-auto my-0 bg-black rounded-t opacity-25 dark:bg-white"></div>
            </div>
            <div class="grid gap-2 grid-cols-1 grid-flow-row sm:grid-flow-col sm:grid-cols-2 sm:grid-rows-2 lg:grid-cols-4 lg:grid-rows-1 mx-auto">
                @foreach ($posts as $post)
                    <x-home.article :post="$post" />
                @endforeach
            </div>
            @if($posts->count() > 0)
                <div class="w-full text-xs text-center mt-8">
                    <a href="{{ route('blog.index') }}" class="rounded-lg bg-green-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-green-700 focus:outline-none focus:ring">
                        @lang('Read more blog posts')
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            @else
                <div class="w-full text-xs text-center">
                    <p class="mb-1 dark:text-slate-400">{{ __('There is nothing to show') }}...</p>
                    <a href="{{ route('blog.index') }}" class="rounded-lg bg-green-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-green-700 focus:outline-none focus:ring">
                        @lang('Go to blog')
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

    <section class="py-8 bg-white border-b dark:bg-slate-800 dark:border-black">
        <div class="container max-w-5xl m-8 mx-auto">
            <h2 class="w-full my-2 text-2xl font-black leading-tight text-center text-slate-800 uppercase sm:text-3xl dark:text-slate-200">
                @lang('Features')
            </h2>
            <div class="w-full mb-4">
                <div class="w-64 h-1 py-0 mx-auto my-0 bg-black rounded-t opacity-25 dark:bg-white"></div>
            </div>

            <div class="flex flex-wrap">
                <div class="w-5/6 p-6 sm:w-1/2">
                    <h3 class="mb-3 text-xl font-bold leading-none text-slate-800 sm:text-3xl dark:text-slate-200">
                        @lang('Nostalgia')
                    </h3>
                    <p class="text-slate-600 sm:mb-8 dark:text-slate-400">
                        @lang('Remember the old days when you were playing on a GameBoy? If so; you should try out this game and awake your inner child.')
                    </p>
                </div>
                <div class="w-full px-4 sm:p-6 sm:w-1/2">
                    <img src="{{ asset('img/pikachu.png') }}" alt="Pikachu" />
                </div>
            </div>

            <div class="flex flex-col-reverse flex-wrap sm:flex-row">
                <div class="grid w-full px-4 sm:p-6 sm:mt-6 sm:w-1/2 justify-items-end">
                    <img src="{{ asset('img/rhydon.png') }}" alt="Rhydon" />
                </div>
                <div class="w-full p-6 mt-6 sm:w-1/2">
                    <div class="align-middle">
                        <h3 class="mb-3 text-xl font-bold leading-none text-slate-800 sm:text-3xl dark:text-slate-200">
                            @lang('Most Generations and Regions')
                        </h3>
                        <p class="mb-8 text-slate-600 dark:text-slate-400">
                            @lang(':game will have support for all generations of Pokémon in the future and all 2D regions will be accessible in the game.', [
                                'game' => config('app.name'),
                            ])
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap">
                <div class="w-5/6 p-6 sm:w-1/2">
                    <h3 class="mb-3 text-xl font-bold leading-none text-slate-800 sm:text-3xl dark:text-slate-200">
                        @lang('A New Experience')
                    </h3>
                    <p class="text-slate-600 sm:mb-8 dark:text-slate-400">
                        @lang(':game focuses on the strong points of Pokémon Gold and Silver versions and their remakes, and gives players a taste as to how the once 2D world they knew was in 3D. They could even see through the eyes of their trainer.', [
                            'game' => config('app.name'),
                        ])
                    </p>
                </div>
                <div class="w-full px-4 sm:p-6 sm:w-1/2">
                    <img src="{{ asset('img/scizor.png') }}" alt="Scizor" />
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

        <h3 class="my-4 text-xl font-extrabold text-slate-100 sm:text-2xl">
            @lang('Go back in time with :game!', [
                'game' => config('app.name')
            ])
        </h3>

        <x-download-button />
    </section>

    <!--Footer-->
    <footer class="bg-white dark:bg-slate-800">
        <div class="container px-8 mx-auto mt-8">
            <div class="flex flex-col w-full py-6 md:flex-row">
                <div class="px-3 mb-6 flex-2 text-slate-600 dark:text-slate-200 text-sm">
                    <x-logo-large class="max-w-xs" />
                    <p class="mt-3">
                        @lang(':game is not affiliated with', ['game' => config('app.name')]) Nintendo, Creatures Inc. or GAME FREAK Inc.
                    </p>
                    <p class="mt-3">
                        pokemon3d.net @lang('is owned and operated by') <a href="https://kilobyte.no/" class="text-green-800 no-underline hover:underline">Kilobyte AS</a>
                    </p>
                    <p class="mt-3">
                        @lang('This website is open-source on :github, thanks to our contributors! :hearts', [
                            'github' => '<a href="https://github.com/P3D-Legacy/pokemon3d.net" class="hover:underline"><svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="inline-block w-4 h-4" viewBox="0 0 1792 1792"><path d="M896 128q209 0 385.5 103t279.5 279.5 103 385.5q0 251-146.5 451.5t-378.5 277.5q-27 5-40-7t-13-30q0-3 .5-76.5t.5-134.5q0-97-52-142 57-6 102.5-18t94-39 81-66.5 53-105 20.5-150.5q0-119-79-206 37-91-8-204-28-9-81 11t-92 44l-38 24q-93-26-192-26t-192 26q-16-11-42.5-27t-83.5-38.5-85-13.5q-45 113-8 204-79 87-79 206 0 85 20.5 150t52.5 105 80.5 67 94 39 102.5 18q-39 36-49 103-21 10-45 15t-57 5-65.5-21.5-55.5-62.5q-19-32-48.5-52t-49.5-24l-20-3q-21 0-29 4.5t-5 11.5 9 14 13 12l7 5q22 10 43.5 38t31.5 51l10 23q13 38 44 61.5t67 30 69.5 7 55.5-3.5l23-4q0 38 .5 88.5t.5 54.5q0 18-13 30t-40 7q-232-77-378.5-277.5t-146.5-451.5q0-209 103-385.5t279.5-279.5 385.5-103zm-477 1103q3-7-7-12-10-3-13 2-3 7 7 12 9 6 13-2zm31 34q7-5-2-16-10-9-16-3-7 5 2 16 10 10 16 3zm30 45q9-7 0-19-8-13-17-6-9 5 0 18t17 7zm42 42q8-8-4-19-12-12-20-3-9 8 4 19 12 12 20 3zm57 25q3-11-13-16-15-4-19 7t13 15q15 6 19-6zm63 5q0-13-17-11-16 0-16 11 0 13 17 11 16 0 16-11zm58-10q-2-11-18-9-16 3-14 15t18 8 14-14z"></path></svg> Github</a>', 'hearts' => '<span class="text-red-400 dark:text-red-700">&hearts;</span>'])
                    </p>
                    <p class="mt-3 text-xs"><a class="hover:underline" href="https://github.com/P3D-Legacy/pokemon3d.net/blob/main/CHANGELOG.md">{{ setting('APP_VERSION') ?? 'N/A' }}</a></p>
                </div>
                <div class="flex-1 px-3">
                    <p class="font-extrabold text-slate-500 uppercase dark:text-slate-200 md:mb-6">@lang('Legal')</p>
                    <ul class="mb-6 list-reset">
                        <x-home.footer-link title="{{ __('Terms and Conditions') }}" url="{{ route('terms.show') }}" />
                        <x-home.footer-link title="{{ __('Privacy Policy') }}" url="{{ route('policy.show') }}" />
                        <x-home.footer-link title="{{ __('Legal') }}" url="{{ route('legal') }}" />
                        <x-home.footer-link title="{{ __('Contact') }}" url="{{ route('contact') }}" />
                    </ul>
                </div>
                <div class="flex-1 px-3">
                    <p class="font-extrabold text-slate-500 uppercase dark:text-slate-200 md:mb-6">@lang('Social')</p>
                    <ul class="mb-6 list-reset">
                        <x-home.footer-link title="Discord" url="{{ route('discord') }}" />
                        <x-home.footer-link title="Github" url="{{ route('github') }}" />
                    </ul>
                </div>
                <div class="flex-1 px-3">
                    <p class="font-extrabold text-slate-500 uppercase dark:text-slate-200 md:mb-6">
                        {{ config('app.name') }}
                    </p>
                    <ul class="mb-6 list-reset">
                        <x-home.footer-link title="{{ __('Official Blog') }}" url="{{ route('blog.index') }}" />
                        <x-home.footer-link title="{{ __('Status') }}" url="https://status.pokemon3d.net" />
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</x-guest-layout>
