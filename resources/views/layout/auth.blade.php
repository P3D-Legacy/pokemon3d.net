<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title') - {{ env('APP_NAME') }}</title>

        <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">

        <!-- Style -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css" integrity="sha384-vSIIfh2YWi9wW0r9iZe7RJPrKwp6bG+s9QZMoITbCckVJqGCCRhc+ccxNcdpHuYu" crossorigin="anonymous">

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>

    </head>
<body class="font-sans antialiased bg-repeat bg-center bg-woods w-full h-screen">
    <div class="container flex items-center justify-center flex-1 h-full mx-auto">
        <div class="w-full max-w-lg">

            @if (session('error'))
                <div class="relative py-3 pl-4 pr-10 leading-normal text-red-700 bg-red-100 rounded-lg">
                    <i class="fas fa-frown mr-2" aria-hidden="true"></i> {{ session('error') }}
                </div>
            @endif
            @if (session('success'))
                <div class="relative py-3 pl-4 pr-10 leading-normal text-green-700 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle mr-2" aria-hidden="true"></i> {{ session('success') }}
                </div>
            @endif
            @if (session('info'))
                <div class="relative py-3 pl-4 pr-10 leading-normal text-blue-700 bg-blue-100 rounded-lg">
                    <i class="fas fa-info-circle mr-2" aria-hidden="true"></i> {{ session('info') }}
                </div>
            @endif
            @if (session('warning'))
                <div class="relative py-3 pl-4 pr-10 leading-normal text-yellow-700 bg-yellow-100 rounded-lg">
                    <i class="fas fa-exclamation mr-2" aria-hidden="true"></i> {{ session('warning') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="relative py-3 pl-4 pr-10 leading-normal text-yellow-700 bg-yellow-100 rounded-lg" role="alert">
                    <strong><i class="fas fa-exclamation-triangle mr-2" aria-hidden="true"></i> Input Warning</strong>
                    <ul class="m-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <button x-data @click="alert('I\'ve been clicked!')" class="text-white">Click Me</button>
        
            @yield('content')
        </div>

    </div>
    
    @include('cookieConsent::index')
    
    @yield('javascript')

</body>
</html>