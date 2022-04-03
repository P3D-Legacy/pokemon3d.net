<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<title>{{ config('app.name', 'Pokémon 3D') }}</title>

		<link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">

		<!-- Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

		<!-- Styles -->
		<link rel="stylesheet" href="{{ mix('css/app.css') }}">
        @livewireStyles

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

		@if(env('APP_ENV') != 'local')
			<script defer data-domain="{{ request()->getHost() }}" src="https://plausible.io/js/plausible.js"></script>
		@endif
	</head>
	<body class="flex flex-col font-sans leading-relaxed tracking-wide bg-top bg-repeat bg-spring {{ config('app.debug') ? 'debug-screens' : '' }}">
        <x-jet-banner />

		{{ $slot }}

        @livewireScripts
        @livewire('livewire-ui-modal')

		<script>
            var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
            document.getElementById('switchTheme').addEventListener('click', function() {
                let htmlClasses = document.querySelector('html').classList;
                if(localStorage.theme == 'dark') {
                    htmlClasses.remove('dark');
                    localStorage.removeItem('theme');
                    themeToggleLightIcon.classList.add('hidden');
                    themeToggleDarkIcon.classList.remove('hidden');
                } else {
                    htmlClasses.add('dark');
                    localStorage.theme = 'dark';
                    themeToggleLightIcon.classList.remove('hidden');
                    themeToggleDarkIcon.classList.add('hidden');
                }
            });
            document.getElementById('switchTheme2').addEventListener('click', function() {
                let htmlClasses = document.querySelector('html').classList;
                if(localStorage.theme == 'dark') {
                    htmlClasses.remove('dark');
                    localStorage.removeItem('theme');
                    themeToggleLightIcon.classList.add('hidden');
                    themeToggleDarkIcon.classList.remove('hidden');
                } else {
                    htmlClasses.add('dark');
                    localStorage.theme = 'dark';
                    themeToggleLightIcon.classList.remove('hidden');
                    themeToggleDarkIcon.classList.add('hidden');
                }
            });
        </script>
	</body>
</html>
