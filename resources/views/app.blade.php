<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title inertia>{{ config('app.name', 'Pokémon 3D') }}</title>

        <link rel="icon" type="image/png" sizes="32x32" href="{{ favicon('img/favicon.png') }}">
        <link rel="shortcut icon" href="{{ favicon('img/favicon.png') }}">

        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        {{-- Applies stored or system theme before paint to avoid a flash of the wrong mode. --}}
        <script data-theme-boot>
            (function () {
                try {
                    var stored = localStorage.getItem('theme');
                    var theme = stored === 'light' || stored === 'dark'
                        ? stored
                        : (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                    document.documentElement.classList.toggle('dark', theme === 'dark');
                    document.documentElement.style.colorScheme = theme;
                } catch (e) {}
            })();
        </script>

        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.tsx'])
        @inertiaHead

        @if(config('app.env') != 'local')
            <script defer data-domain="{{ request()->getHost() }}" src="https://plausible.io/js/plausible.js"></script>
        @endif
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
