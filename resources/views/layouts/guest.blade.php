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

        <style>
            /* Browser mockup code
            * Contribute: https://gist.github.com/jarthod/8719db9fef8deb937f4f
            */
            .browser-mockup {
              border-top: 2em solid rgba(255, 255, 255, 0.8);
            }
            .browser-mockup:before {
              display: block;
              position: absolute;
              content: "";
              top: -1.4em;
              right: 3.6em;
              width: 0.8em;
              height: 0.8em;
              background-color: #9b3;
              box-shadow: 0 0 0 2px #9b3, 1.5em 0 0 2px #9b3, 3em 0 0 2px  #f44;
            }
            .browser-mockup > * {
              display: block;
            }
          </style>

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="bg-repeat bg-center bg-spring leading-relaxed tracking-wide flex flex-col font-sans">
        {{ $slot }}

        @include('cookieConsent::index')
    </body>
</html>
