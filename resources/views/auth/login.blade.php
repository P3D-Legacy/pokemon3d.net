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
			<span class="w-1/12 border-b border-gray-300 sm:w-14 dark:border-gray-500"></span>
			<span class="px-2">{{ __('or log in with your P3D account') }}</span>
			<span class="w-1/12 border-b border-gray-300 sm:w-14 dark:border-gray-500"></span>
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
		<a href="{{ route('discord.login') }}" class="flex items-center justify-center w-full px-4 py-3 mt-2 text-sm font-semibold tracking-widest text-white uppercase transition bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 active:bg-indigo-400 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25">
			<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 71 55" class="inline-block w-5 h-5 mr-1">
				<path d="M60.1045 4.8978C55.5792 2.8214 50.7265 1.2916 45.6527 0.41542C45.5603 0.39851 45.468 0.440769 45.4204 0.525289C44.7963 1.6353 44.105 3.0834 43.6209 4.2216C38.1637 3.4046 32.7345 3.4046 27.3892 4.2216C26.905 3.0581 26.1886 1.6353 25.5617 0.525289C25.5141 0.443589 25.4218 0.40133 25.3294 0.41542C20.2584 1.2888 15.4057 2.8186 10.8776 4.8978C10.8384 4.9147 10.8048 4.9429 10.7825 4.9795C1.57795 18.7309 -0.943561 32.1443 0.293408 45.3914C0.299005 45.4562 0.335386 45.5182 0.385761 45.5576C6.45866 50.0174 12.3413 52.7249 18.1147 54.5195C18.2071 54.5477 18.305 54.5139 18.3638 54.4378C19.7295 52.5728 20.9469 50.6063 21.9907 48.5383C22.0523 48.4172 21.9935 48.2735 21.8676 48.2256C19.9366 47.4931 18.0979 46.6 16.3292 45.5858C16.1893 45.5041 16.1781 45.304 16.3068 45.2082C16.679 44.9293 17.0513 44.6391 17.4067 44.3461C17.471 44.2926 17.5606 44.2813 17.6362 44.3151C29.2558 49.6202 41.8354 49.6202 53.3179 44.3151C53.3935 44.2785 53.4831 44.2898 53.5502 44.3433C53.9057 44.6363 54.2779 44.9293 54.6529 45.2082C54.7816 45.304 54.7732 45.5041 54.6333 45.5858C52.8646 46.6197 51.0259 47.4931 49.0921 48.2228C48.9662 48.2707 48.9102 48.4172 48.9718 48.5383C50.038 50.6034 51.2554 52.5699 52.5959 54.435C52.6519 54.5139 52.7526 54.5477 52.845 54.5195C58.6464 52.7249 64.529 50.0174 70.6019 45.5576C70.6551 45.5182 70.6887 45.459 70.6943 45.3942C72.1747 30.0791 68.2147 16.7757 60.1968 4.9823C60.1772 4.9429 60.1437 4.9147 60.1045 4.8978ZM23.7259 37.3253C20.2276 37.3253 17.3451 34.1136 17.3451 30.1693C17.3451 26.225 20.1717 23.0133 23.7259 23.0133C27.308 23.0133 30.1626 26.2532 30.1066 30.1693C30.1066 34.1136 27.28 37.3253 23.7259 37.3253ZM47.3178 37.3253C43.8196 37.3253 40.9371 34.1136 40.9371 30.1693C40.9371 26.225 43.7636 23.0133 47.3178 23.0133C50.9 23.0133 53.7545 26.2532 53.6986 30.1693C53.6986 34.1136 50.9 37.3253 47.3178 37.3253Z" />
			</svg> Discord
		</a>
		<p class="p-0 mx-8 mt-3 text-xs text-center text-gray-400 dark:text-gray-600">These login method requires you to have a P3D account with a association to the accounts above.</p>
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
