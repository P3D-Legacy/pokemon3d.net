<div class="p-6 border-b border-gray-200 bg-spring sm:px-20 dark:border-gray-700 dark:bg-gray-900">

    <div class="mt-8 font-mono text-3xl font-bold tracking-tighter text-gray-50">
        @lang('Welcome to :game!', ['game' => config('app.name')])
    </div>

    <div class="mt-6 text-gray-100">
        @lang(':game is a video game originally created by Nilllzz. It is heavily inspired by Minecraft, and the Pokémon series. :game focused on the strong points of Pokémon Gold and Silver versions and their remakes, and gives players a taste as to how the once 2D world they knew was in 3D. They could even see through the eyes of their own trainer. We hope you love it.', ['game' => config('app.name')])
    </div>

    <x-download-button />
    
</div>

<div class="grid grid-cols-1 bg-gray-200 bg-opacity-25 md:grid-cols-2 dark:bg-gray-800">
    <div class="p-6">
        <div class="flex items-center">
            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-400"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            <div class="ml-4 text-lg font-semibold leading-7 text-gray-600 dark:text-gray-300">@lang('Documentation')</div>
        </div>

        <div class="ml-12">
            <div class="mt-2 text-sm text-gray-500 dark:text-gray-300">
                @lang(":game has wonderful documentation covering every aspect of the game. Whether you're new to the game or have previous experience, we recommend reading all of the documentation from beginning to end.", ['game' => config('app.name')])
            </div>

            <a href="{{ route('wiki') }}">
                <div class="flex items-center mt-3 text-sm font-semibold text-green-700 dark:text-green-500">
                    <div>@lang('Explore the wiki')</div>

                    <div class="ml-1 text-green-700 dark:text-green-500">
                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="p-6 border-t border-gray-200 md:border-t-0 md:border-l dark:border-gray-900">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 71 55" class="w-8 h-8 text-gray-400">
                <path d="M60.1045 4.8978C55.5792 2.8214 50.7265 1.2916 45.6527 0.41542C45.5603 0.39851 45.468 0.440769 45.4204 0.525289C44.7963 1.6353 44.105 3.0834 43.6209 4.2216C38.1637 3.4046 32.7345 3.4046 27.3892 4.2216C26.905 3.0581 26.1886 1.6353 25.5617 0.525289C25.5141 0.443589 25.4218 0.40133 25.3294 0.41542C20.2584 1.2888 15.4057 2.8186 10.8776 4.8978C10.8384 4.9147 10.8048 4.9429 10.7825 4.9795C1.57795 18.7309 -0.943561 32.1443 0.293408 45.3914C0.299005 45.4562 0.335386 45.5182 0.385761 45.5576C6.45866 50.0174 12.3413 52.7249 18.1147 54.5195C18.2071 54.5477 18.305 54.5139 18.3638 54.4378C19.7295 52.5728 20.9469 50.6063 21.9907 48.5383C22.0523 48.4172 21.9935 48.2735 21.8676 48.2256C19.9366 47.4931 18.0979 46.6 16.3292 45.5858C16.1893 45.5041 16.1781 45.304 16.3068 45.2082C16.679 44.9293 17.0513 44.6391 17.4067 44.3461C17.471 44.2926 17.5606 44.2813 17.6362 44.3151C29.2558 49.6202 41.8354 49.6202 53.3179 44.3151C53.3935 44.2785 53.4831 44.2898 53.5502 44.3433C53.9057 44.6363 54.2779 44.9293 54.6529 45.2082C54.7816 45.304 54.7732 45.5041 54.6333 45.5858C52.8646 46.6197 51.0259 47.4931 49.0921 48.2228C48.9662 48.2707 48.9102 48.4172 48.9718 48.5383C50.038 50.6034 51.2554 52.5699 52.5959 54.435C52.6519 54.5139 52.7526 54.5477 52.845 54.5195C58.6464 52.7249 64.529 50.0174 70.6019 45.5576C70.6551 45.5182 70.6887 45.459 70.6943 45.3942C72.1747 30.0791 68.2147 16.7757 60.1968 4.9823C60.1772 4.9429 60.1437 4.9147 60.1045 4.8978ZM23.7259 37.3253C20.2276 37.3253 17.3451 34.1136 17.3451 30.1693C17.3451 26.225 20.1717 23.0133 23.7259 23.0133C27.308 23.0133 30.1626 26.2532 30.1066 30.1693C30.1066 34.1136 27.28 37.3253 23.7259 37.3253ZM47.3178 37.3253C43.8196 37.3253 40.9371 34.1136 40.9371 30.1693C40.9371 26.225 43.7636 23.0133 47.3178 23.0133C50.9 23.0133 53.7545 26.2532 53.6986 30.1693C53.6986 34.1136 50.9 37.3253 47.3178 37.3253Z" />
            </svg>
            <div class="ml-4 text-lg font-semibold leading-7 text-gray-600 dark:text-gray-300">Discord</div>
        </div>

        <div class="ml-12">
            <div class="mt-2 text-sm text-gray-500 dark:text-gray-300">
                @lang("We've made it easy for you to get in touch with other players in real-time with our community Discord-server for the game. You can ask questions, share your ideas and even get help from other players. We're always happy to help!")
            </div>
            <a href="{{ route('discord') }}">
                <div class="flex items-center mt-3 text-sm font-semibold text-green-700 dark:text-green-500">
                    <div>@lang('Get on our Discord server')</div>

                    <div class="ml-1 text-green-700 dark:text-green-500">
                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>
            </a>
        </div>
        
    </div>

    <div class="p-6 border-t border-gray-200 dark:border-gray-900">
        <div class="flex items-center">
            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-400"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <div class="ml-4 text-lg font-semibold leading-7 text-gray-600 dark:text-gray-300">@lang('Custom Skin')</div>
        </div>

        <div class="ml-12">
            <div class="mt-2 text-sm text-gray-500 dark:text-gray-300">
                @lang(":game has a built in feature where every player has the opportunity to change their look for multiplayer sessions. You'll be amazed how easily you can change it, store other skin and browse what other have made just at your fingertips.", ['game' => config('app.name')])
            </div>
            <a href="{{ route('skin-home') }}">
                <div class="flex items-center mt-3 text-sm font-semibold text-green-700 dark:text-green-500">
                    <div>@lang('Get to customization')</div>

                    <div class="ml-1 text-green-700 dark:text-green-500">
                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="p-6 border-t border-gray-200 md:border-l dark:border-gray-900">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
            </svg>
            <div class="ml-4 text-lg font-semibold leading-7 text-gray-600 dark:text-gray-300">@lang('Forum (Archived)')</div>
        </div>

        <div class="ml-12">
            <div class="mt-2 text-sm text-gray-500 dark:text-gray-300">
                @lang('Our forum offers a lot of threads of discussions, bugs and other information. Check them out, see for yourself, and massively level up your knowledge skills in the process.')
            </div>

            <a href="{{ route('forum') }}">
                <div class="flex items-center mt-3 text-sm font-semibold text-green-700 dark:text-green-500">
                    <div>@lang('Start browsing')</div>

                    <div class="ml-1 text-green-700 dark:text-green-500">
                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
