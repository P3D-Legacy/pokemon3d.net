<x-guest-layout>
    <main class="relative overflow-hidden">
        <div class="container mx-auto h-screen pt-32 md:pt-0 px-6 z-10 flex items-center justify-between">
            <div class="container mx-auto px-6 flex flex-col-reverse lg:flex-row justify-between items-center relative bg-white dark:bg-gray-800 h-screen max-h-96 rounded-xl">
                <div class="w-full mb-16 md:mb-8 text-center lg:text-left">
                    <h1 class="font-light font-sans text-center lg:text-left text-5xl lg:text-8xl mt-12 md:mt-0 text-gray-700 dark:text-gray-300">
                        MissingNo.
                    </h1>
                    <p class="text-gray-400">404 &mdash; This path was not found.</p>
                    <a href="{{ route('home') }}" class="mt-8 font-extrabold rounded-lg py-4 px-8 shadow-xl w-76 inline-flex items-center justify-center text-green-50 group-hover:text-green-100 bg-green-500 hover:bg-green-600 border border-green-400 transition transform hover:-translate-y-1 duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Go back home
                    </a>
                </div>
                <div class="block w-full mx-auto md:mt-0 relative max-w-md lg:max-w-2xl">
                    <img src="{{ asset('img/missingno.png') }}" class="h-40 w-40 md:h-60 md:w-60 lg:h-80 lg:w-80 mx-auto" />
                </div>
            </div>
        </div>
    </main>
</x-guest-layout>