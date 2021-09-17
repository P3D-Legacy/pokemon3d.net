<x-guest-layout>
	<x-jet-authentication-card>
		<x-slot name="logo">
			<a href="{{ route('home') }}">
				<x-jet-authentication-card-logo />
			</a>
		</x-slot>

		@if(\Carbon\Carbon::createFromFormat('Y-m-d', '2021-12-31')->isFuture())
			<div class="px-4 py-3 text-sm leading-normal text-blue-700 bg-blue-100 rounded-lg" role="alert">
				<p>
					<svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
					</svg>
					This new website has now a own authentication. Please <a class="font-bold hover:underline" href="{{ env('LOGIN_AUTH_BLOG_POST') ?? route('blog.index') }}">read the blog post</a> for more information!
				</p>
			</div>

			<div class="flex items-center justify-center py-4 text-sm text-gray-400 xl:py-6">
				<span class="w-1/2 border-b border-gray-300 dark:border-gray-500"></span>
			</div>
		@endif

		<a href="{{ route('register') }}" class="flex items-center justify-center w-full px-4 py-3 text-sm font-semibold tracking-widest text-white uppercase transition bg-green-500 border border-transparent rounded-md hover:bg-green-600 active:bg-green-400 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25">
			<svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
			</svg>{{ __('Register') }}
		</a>

		<div class="flex items-center justify-center py-4 text-sm text-gray-400 xl:py-6">
			<span class="border-b border-gray-300 w-14 dark:border-gray-500"></span>
			<span class="px-2">{{ __('or log in with your P3D account') }}</span>
			<span class="border-b border-gray-300 w-14 dark:border-gray-500"></span>
		</div>

		<x-jet-validation-errors class="mb-4" />

		@if (session('status'))
			<div class="mb-4 text-sm font-medium text-green-600">
				{{ session('status') }}
			</div>
		@endif

		<form method="POST" action="{{ route('login') }}">
			@csrf

			<div>
				<x-jet-label for="username" value="{{ __('Email or Username') }}" />
				<x-jet-input id="username" class="block w-full mt-1" type="text" name="username" :value="old('username')" required autofocus />
			</div>

			<div class="mt-4">
				<x-jet-label for="password" value="{{ __('Password') }}" />
				<x-jet-input id="password" class="block w-full mt-1" type="password" name="password" required autocomplete="current-password" />
			</div>

			<div class="block mt-4">
				<label for="remember_me" class="flex items-center">
					<x-jet-checkbox id="remember_me" name="remember" />
					<span class="ml-2 text-sm text-gray-600 dark:text-gray-300">{{ __('Remember me') }}</span>
				</label>
			</div>

			<div class="flex items-center justify-end mt-4">
				@if (Route::has('password.request'))
					<div class="w-2/3">
						<a class="text-sm text-gray-600 underline hover:text-gray-900 dark:text-gray-300 dark:hover:text-gray-100" href="{{ route('password.request') }}">
							{{ __('Forgot your password?') }}
						</a>
					</div>
				@endif
				<div class="w-1/3">
					<x-jet-button class="flex items-center justify-center w-full px-4 py-3 text-sm">
						<svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
						</svg>{{ __('Log in') }}
					</x-jet-button>
				</div>
			</div>
		</form>

		<div class="flex items-center justify-center py-4 text-sm text-gray-400 xl:py-6">
			<span class="border-b border-gray-300 w-14 dark:border-gray-500"></span>
			<span class="px-2">{{ __('or log in with') }}</span>
			<span class="border-b border-gray-300 w-14 dark:border-gray-500"></span>
		</div>

		<button class="flex items-center justify-center w-full px-4 py-3 text-sm font-semibold tracking-widest text-white uppercase transition bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 active:bg-blue-400 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25" type="button" onclick="toggleModal('xenforo')">
			<svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
			</svg>{{ __('Forum Account') }}
		</button>
		<button class="flex items-center justify-center w-full px-4 py-3 mt-2 text-sm font-semibold tracking-widest text-black uppercase transition border border-transparent rounded-md bg-gamejolt-green hover:bg-opacity-70 focus:outline-none focus:border-green-200 focus:ring focus:ring-green-100 disabled:opacity-25" type="button" onclick="toggleModal('gamejolt')">
			<img src="{{ asset('img/gamejolt-logo-light-1x.png') }}">
		</button>        
	</x-jet-authentication-card>

	<div class="fixed inset-0 z-50 items-center justify-center hidden overflow-x-hidden overflow-y-auto outline-none focus:outline-none" id="xenforo">
		<div class="relative w-full max-w-2xl mx-auto my-6">
			<!--content-->
			<div class="relative flex flex-col w-full bg-white border-0 rounded-lg shadow-lg outline-none dark:bg-gray-800 focus:outline-none">
				<!--header-->
				<div class="flex items-start justify-between rounded-t">
					<button class="float-right p-3 ml-auto text-3xl font-semibold leading-none text-black bg-transparent border-0 outline-none opacity-50 dark:text-white focus:outline-none" onclick="toggleModal('xenforo')">
						<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 fill-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
						</svg>
					</button>
				</div>
				<!--body-->
				<div class="relative flex-auto p-6 pt-0">
					@livewire('login.xenforo')
				</div>
			</div>
		</div>
	</div>

	<div class="fixed inset-0 z-50 items-center justify-center hidden overflow-x-hidden overflow-y-auto outline-none focus:outline-none" id="gamejolt">
		<div class="relative w-full max-w-2xl mx-auto my-6">
			<!--content-->
			<div class="relative flex flex-col w-full bg-white border-0 rounded-lg shadow-lg outline-none dark:bg-gray-800 focus:outline-none">
				<!--header-->
				<div class="flex items-start justify-between rounded-t">
					<button class="float-right p-3 ml-auto text-3xl font-semibold leading-none text-black bg-transparent border-0 outline-none opacity-50 dark:text-white focus:outline-none" onclick="toggleModal('gamejolt')">
						<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 fill-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
						</svg>
					</button>
				</div>
				<!--body-->
				<div class="relative flex-auto p-6 pt-0">
					@livewire('login.game-jolt')
				</div>
			</div>
		</div>
	</div>	
	
	<div class="fixed inset-0 z-40 hidden bg-black opacity-50" id="modal-backdrop"></div>
	<script type="text/javascript">
		function toggleModal(modalID){
			document.getElementById(modalID).classList.toggle("hidden");
			document.getElementById(modalID).classList.toggle("flex");
			document.getElementById("modal-backdrop").classList.toggle("hidden");
			document.getElementById("modal-backdrop").classList.toggle("flex");
		}
	</script>
</x-guest-layout>
