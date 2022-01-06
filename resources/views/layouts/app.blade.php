<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        @livewireStyles
        @powerGridStyles

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
        <script> 
            var $buoop = {required:{e:-6,f:-6,o:-6,s:-3,c:-6},insecure:true,unsupported:true,api:2021.08 }; 
            function $buo_f(){ 
             var e = document.createElement("script"); 
             e.src = "//browser-update.org/update.min.js"; 
             document.body.appendChild(e);
            };
            try {document.addEventListener("DOMContentLoaded", $buo_f,false)}
            catch(e){window.attachEvent("onload", $buo_f)}
        </script>

        <script>
            if (localStorage.theme === 'dark' || (!'theme' in localStorage && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.querySelector('html').classList.add('dark')
            } else if (localStorage.theme === 'dark') {
                document.querySelector('html').classList.add('dark')
            }
        </script>
    </head>
    <body class="font-sans antialiased {{ env('APP_DEBUG') ? 'debug-screens' : '' }}">
        <x-jet-banner />

        @if(Auth::user() && ! Auth::user()->hasGivenConsent(config('app.required_consent')))
            <livewire:new-terms-banner />
        @endif

        @if(env('APP_ENV') != 'production')
            <div class="fixed inset-x-0 top-0 z-50 pointer-events-none">
                <div class="max-w-xs p-0 mx-auto">
                    <div class="p-0 rounded-b-lg shadow bg-yellow-600/80">
                        <div class="flex flex-wrap items-center justify-between">
                            <div class="flex items-center flex-1 w-0">
                                <p class="w-full text-sm font-bold text-center text-white truncate">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    {{ (env('APP_ENV') == 'staging') ? 'QA: FOR TESTING ONLY' : 'DEV MODE' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="min-h-screen bg-gray-100 dark:bg-gray-800">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow dark:bg-black">
                    <div class="px-4 py-6 mx-auto text-gray-800 max-w-7xl sm:px-6 lg:px-8 dark:text-gray-200">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @include('cookieConsent::index')

        @livewireScripts
        @powerGridScripts

        <script>
            document.getElementById('switchTheme').addEventListener('click', function() {
                let htmlClasses = document.querySelector('html').classList;
                if(localStorage.theme == 'dark') {
                    htmlClasses.remove('dark');
                    localStorage.removeItem('theme')
                } else {
                    htmlClasses.add('dark');
                    localStorage.theme = 'dark';
                }
            });
            document.getElementById('switchTheme2').addEventListener('click', function() {
                let htmlClasses = document.querySelector('html').classList;
                if(localStorage.theme == 'dark') {
                    htmlClasses.remove('dark');
                    localStorage.removeItem('theme')
                } else {
                    htmlClasses.add('dark');
                    localStorage.theme = 'dark';
                }
            });
        </script>
        
    </body>
</html>
