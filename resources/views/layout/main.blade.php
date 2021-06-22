<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title') - {{ env('APP_NAME') }}</title>

    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css" integrity="sha384-vSIIfh2YWi9wW0r9iZe7RJPrKwp6bG+s9QZMoITbCckVJqGCCRhc+ccxNcdpHuYu" crossorigin="anonymous">

    <!-- Cookie Consent -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.css" />

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>
<body class="font-sans antialiased bg-repeat bg-center bg-woods w-full h-full">
    <div class="container flex flex-1 h-full mx-auto">
        <div class="w-full">

            <header class="w-full shadow-lg bg-white dark:bg-gray-700 items-center h-16 rounded-2xl z-40 mt-4">
                <div class="relative z-20 flex flex-col justify-center h-full px-3 mx-auto flex-center">
                    <div class="relative items-center pl-1 flex w-full lg:max-w-68 sm:pr-2 sm:ml-0">
                        <div class="container relative left-0 z-50 flex h-auto h-full">
                            <div class="flex items-center justify-between h-16 w-full">
                                <div class="flex items-center">
                                    <a class="flex-shrink-0" href="/">
                                        <img class="h-8 w-8" src="{{ asset('img/TreeLogoSmall.png') }}" alt="{{ env('APP_NAME') }}"/>
                                    </a>
                                    <div class="block ml-6 flex items-baseline space-x-4">
                                        <a class="text-gray-600 hover:text-gray-800 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium" href="{{ route('skins-my') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                            My Skins
                                        </a>
                                        <a class="text-gray-600 hover:text-gray-800 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium" href="{{ route('skins') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                            </svg>
                                            Public Skins
                                        </a>
                                        <a class="text-gray-600 hover:text-gray-800 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium" href="{{ route('skins') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                            </svg>
                                            Upload Skin
                                        </a>
                                        @if(App\Models\GJUser::where('gjid', session()->get('gjid'))->first())
                                            @if(App\Models\GJUser::where('gjid', session()->get('gjid'))->first()->is_admin)
                                                <a class="text-green-600 hover:text-gray-800 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium" href="{{ route('player-skins') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                    </svg>
                                                    Player Skins
                                                </a>
                                                <a class="text-green-600 hover:text-gray-800 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium" href="{{ route('uploaded-skins') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                                                    </svg>
                                                    Uploaded Skins
                                                </a>
                                            @endif
                                        @endif
                                        @if(session()->get('gjid') == env("GAMEJOLT_USER_ID_SUPERADMIN"))
                                            <a class="text-red-600 hover:text-gray-800 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium" href="{{ route('uploaded-skins') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                Users
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="block">
                                    <div class="ml-4 flex items-center md:ml-6">
                                    </div>
                                </div>
                                <div class="block">
                                    <div class="ml-4 flex items-center md:ml-6">
                                        <div class="ml-3 relative">
                                            <div x-data="{ open: false }" class="relative inline-block text-left" >
                                                <button @click="open = ! open" type="button" class="flex items-center justify-center align-middle w-full rounded-md px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-50 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-gray-500" id="options-menu">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Pok&eacute;mon 3D
                                                </button>
                                                <div x-show="open" @click.outside="open = false" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 border border-1 border-gray-300">
                                                    <div class="py-1 " role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                                        <a href="#" class="block px-4 py-2 text-md text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-100 dark:hover:text-white dark:hover:bg-gray-600" role="menuitem">
                                                            <span class="flex flex-col">
                                                                <span>
                                                                    Logout
                                                                </span>
                                                                <span>
                                                                    Logout
                                                                </span>
                                                                <span>
                                                                    Logout
                                                                </span>
                                                                <span>
                                                                    Logout
                                                                </span>
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ml-3 relative">
                                            <div x-data="{ open: false }"class="relative inline-block text-left" >
                                                <button @click="open = ! open" type="button" class="flex items-center justify-center align-middle w-full rounded-md px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-50 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-gray-500" id="options-menu">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Username
                                                </button>
                                                <div x-show="open" @click.outside="open = false" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 border border-1 border-gray-300">
                                                    <div class="py-1 " role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                                        <a href="#" class="block px-4 py-2 text-md text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-100 dark:hover:text-white dark:hover:bg-gray-600" role="menuitem">
                                                            <span class="flex flex-col">
                                                                <span>
                                                                    Logout
                                                                </span>
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{--
            <nav class="navbar navbar-expand-md navbar-dark bg-success bg-gradient my-3">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ route('home') }}"><img src="{{ asset('img/TreeLogoSmall.png') }}" alt="skin.pokemon3d.net" width="30" height="30" class="d-inline-block align-center"> {{ env('APP_NAME') }}</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarDefault" aria-controls="navbarDefault" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                
                    <div class="collapse navbar-collapse" id="navbarDefault">
                        <ul class="navbar-nav me-auto mb-2 mb-md-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-vest-patches"></i> Skins</a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('skins-my') }}"><i class="fas fa-user-lock"></i> My Skins</a></li>
                                    <li><a class="dropdown-item" href="{{ route('skins') }}"><i class="fas fa-user-tag"></i> Public Skins</a></li>
                                </ul>
                            </li>
                            <li class="nav-item"><a class="nav-link" aria-current="page" href="{{ route('skin-create') }}"><i class="fas fa-upload"></i> Upload</a></li>
                        </ul>
                        <ul class="navbar-nav">
                            @if(App\Models\GJUser::where('gjid', session()->get('gjid'))->first())
                                @if(App\Models\GJUser::where('gjid', session()->get('gjid'))->first()->is_admin)
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user-tie"></i> Admin</a>
                                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            <li><a class="dropdown-item" href="{{ route('player-skins') }}"><i class="fas fa-user-circle"></i> Player Skins</a></li>
                                            <li><a class="dropdown-item" href="{{ route('uploaded-skins') }}"><i class="far fa-user-circle"></i> Uploaded Skins</a></li>
                                        </ul>
                                    </li>
                                @endif
                            @endif
                            @if(session()->get('gjid') == env("GAMEJOLT_USER_ID_SUPERADMIN"))
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user-secret"></i> Super Admin</a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item" href="{{ route('users') }}"><i class="fas fa-users"></i> Users</a></li>
                                    </ul>
                                </li>
                            @endif
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-gamepad"></i> Pok&eacute;mon 3D</a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="https://pokemon3d.net/">Homepage</a></li>
                                    <li><a class="dropdown-item" href="https://pokemon3d.net/forum/">Forum</a></li>
                                    <li><a class="dropdown-item" href="https://pokemon3d.net/wiki/">Wiki</a></li>
                                    <li><a class="dropdown-item" href="https://github.com/P3D-Legacy/P3D-Legacy">Github</a></li>
                                    <li><a class="dropdown-item" href="https://discordapp.com/invite/EUhwdrq">Discord</a></li>
                                </ul>
                            </li>
                            <li class="nav-item"><a class="nav-link" aria-current="page" href="{{ route('logout') }}"><i class="fas fa-sign-out-alt"></i> Log out</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
--}}
            @if (session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-frown mr-2" aria-hidden="true"></i> {{ session('error') }}
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle mr-2" aria-hidden="true"></i> {{ session('success') }}
                </div>
            @endif
            @if (session('info'))
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2" aria-hidden="true"></i> {{ session('info') }}
                </div>
            @endif
            @if (session('warning'))
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation mr-2" aria-hidden="true"></i> {{ session('warning') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-warning my-3" role="alert">
                    <strong><i class="fas fa-exclamation-triangle mr-2" aria-hidden="true"></i> Input Warning</strong>
                    <ul class="m-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

        </div>
    </div>
    <div class="container w-full shadow-lg bg-white dark:bg-gray-700 items-center rounded-2xl z-40 mt-4 mb-8 mx-auto text-gray-600">
        <div class="flex flex-row p-4">
            <div class="flex flex-1 items-center">
                <p class="inline-block text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                      </svg> {{ round((microtime(true) - LARAVEL_START), 3) }}s &middot;&nbsp;This website is open-source on <a href="https://github.com/P3D-Legacy/skin.pokemon3d.net"><svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="h-4 w-4 inline-block" viewBox="0 0 1792 1792">
                        <path d="M896 128q209 0 385.5 103t279.5 279.5 103 385.5q0 251-146.5 451.5t-378.5 277.5q-27 5-40-7t-13-30q0-3 .5-76.5t.5-134.5q0-97-52-142 57-6 102.5-18t94-39 81-66.5 53-105 20.5-150.5q0-119-79-206 37-91-8-204-28-9-81 11t-92 44l-38 24q-93-26-192-26t-192 26q-16-11-42.5-27t-83.5-38.5-85-13.5q-45 113-8 204-79 87-79 206 0 85 20.5 150t52.5 105 80.5 67 94 39 102.5 18q-39 36-49 103-21 10-45 15t-57 5-65.5-21.5-55.5-62.5q-19-32-48.5-52t-49.5-24l-20-3q-21 0-29 4.5t-5 11.5 9 14 13 12l7 5q22 10 43.5 38t31.5 51l10 23q13 38 44 61.5t67 30 69.5 7 55.5-3.5l23-4q0 38 .5 88.5t.5 54.5q0 18-13 30t-40 7q-232-77-378.5-277.5t-146.5-451.5q0-209 103-385.5t279.5-279.5 385.5-103zm-477 1103q3-7-7-12-10-3-13 2-3 7 7 12 9 6 13-2zm31 34q7-5-2-16-10-9-16-3-7 5 2 16 10 10 16 3zm30 45q9-7 0-19-8-13-17-6-9 5 0 18t17 7zm42 42q8-8-4-19-12-12-20-3-9 8 4 19 12 12 20 3zm57 25q3-11-13-16-15-4-19 7t13 15q15 6 19-6zm63 5q0-13-17-11-16 0-16 11 0 13 17 11 16 0 16-11zm58-10q-2-11-18-9-16 3-14 15t18 8 14-14z">
                        </path>
                    </svg> Github</a> &middot;
                    <a href="https://github.com/P3D-Legacy/skin.pokemon3d.net/graphs/contributors" target="_blank"><img src="https://img.shields.io/github/contributors/P3D-Legacy/skin.pokemon3d.net" alt="Contributors" class="inline-block"></a>
                    <a href="https://github.com/P3D-Legacy/skin.pokemon3d.net/issues" target="_blank"><img src="https://img.shields.io/github/issues/P3D-Legacy/skin.pokemon3d.net" alt="Issues" class="inline-block"></a>
                    <a href="https://discordapp.com/invite/EUhwdrq" target="_blank"><img src="https://img.shields.io/discord/299181628188524544" alt="Discord" class="inline-block"></a>
                </p>
            </div>
            <div class="flex flex-1 items-center">
                <p class="text-xs text-right w-full">
                    @if (env('APP_DEBUG')) <span class="text-red-500 font-bold">DEBUG MODE ACTIVE!</span> &middot; @endif <a class="text-gray-400" href="https://github.com/P3D-Legacy/skin.pokemon3d.net/blob/main/CHANGELOG.md">{{ setting('APP_VERSION') ?? 'N/A' }}</a>
                </p>
            </div>
        </div>
    </div>

    @include('cookieConsent::index')

    @yield('javascript')

</body>
</html>