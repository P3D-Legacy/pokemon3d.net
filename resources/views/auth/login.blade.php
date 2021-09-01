<x-guest-layout>
	<x-jet-authentication-card>
		<x-slot name="logo">
			<a href="{{ route('home') }}">
				<x-jet-authentication-card-logo />
			</a>
		</x-slot>

		@if(\Carbon\Carbon::createFromFormat('Y-m-d', '2021-12-31')->isFuture())
			<div class="px-4 py-3 leading-normal text-blue-700 bg-blue-100 rounded-lg text-sm" role="alert">
				<p>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
					</svg>
					This new website has now a own authentication. Please <a class="font-bold hover:underline" href="{{ env('LOGIN_AUTH_BLOG_POST') ?? route('blog.index') }}">read the blog post</a> for more information!
				</p>
			</div>

			<div class="w-1/2 mx-auto">
				<x-jet-section-border />
			</div>
		@endif

		<x-jet-validation-errors class="mb-4" />

		@if (session('status'))
			<div class="mb-4 font-medium text-sm text-green-600">
				{{ session('status') }}
			</div>
		@endif

		<form method="POST" action="{{ route('login') }}">
			@csrf

			<div>
				<x-jet-label for="username" value="{{ __('Email or Username') }}" />
				<x-jet-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus />
			</div>

			<div class="mt-4">
				<x-jet-label for="password" value="{{ __('Password') }}" />
				<x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
			</div>

			<div class="block mt-4">
				<label for="remember_me" class="flex items-center">
					<x-jet-checkbox id="remember_me" name="remember" />
					<span class="ml-2 text-sm text-gray-600 dark:text-gray-300">{{ __('Remember me') }}</span>
				</label>
			</div>

			<div class="flex items-center justify-center mt-4">
				<a class="underline text-sm text-green-700 hover:text-gray-900 dark:text-green-300 dark:hover:text-green-200 px-2" href="{{ route('register') }}">
					{{ __('Need a account?') }}
				</a>

				@if (Route::has('password.request'))
					<a class="underline text-sm text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-gray-100 px-2" href="{{ route('password.request') }}">
						{{ __('Forgot your password?') }}
					</a>
				@endif
			</div>

			<div class="flex items-center justify-center mt-4">
				<x-jet-button class="w-full flex items-center justify-center px-4 py-3 text-sm">
					{{ __('Log in') }}
				</x-jet-button>
			</div>
		</form>

		<div class="flex items-center justify-center py-4 xl:py-6 text-sm text-gray-400">
			<span class="w-14 border-b border-gray-300 dark:border-gray-500"></span>
			<span class="px-2">or log in with</span>
			<span class="w-14 border-b border-gray-300 dark:border-gray-500"></span>
		</div>

		<a href="#" class="w-full flex items-center justify-center px-4 py-3 bg-blue-500 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-400 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
			Forum Account
		</a>        
		<button href="#" class="w-full flex items-center justify-center px-4 py-3 bg-gamejolt-green border border-transparent rounded-md font-semibold text-sm text-black uppercase tracking-widest hover:bg-opacity-70 focus:outline-none focus:border-green-200 focus:ring focus:ring-green-100 disabled:opacity-25 transition mt-2" type="button" onclick="toggleModal('gamejolt-modal')">
			<img src="{{ asset('img/gamejolt-logo-light-1x.png') }}">
		</button>        
	</x-jet-authentication-card>

	<div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center" id="gamejolt-modal">
		<div class="relative w-full my-6 mx-auto max-w-2xl">
			<!--content-->
			<div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white dark:bg-gray-800 outline-none focus:outline-none">
				<!--header-->
				<div class="flex items-start justify-between rounded-t">
					<button class="p-3 ml-auto bg-transparent border-0 text-black dark:text-white opacity-50 float-right text-3xl leading-none font-semibold outline-none focus:outline-none" onclick="toggleModal('gamejolt-modal')">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 fill-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
						</svg>
					</button>
				</div>
				<!--body-->
				<div class="relative p-6 pt-0 flex-auto">
					@livewire('login.game-jolt')
				</div>
			</div>
		</div>
	</div>
	<div class="hidden opacity-50 fixed inset-0 z-40 bg-black" id="modal-backdrop"></div>
	<script type="text/javascript">
		function toggleModal(modalID){
			document.getElementById(modalID).classList.toggle("hidden");
			document.getElementById("modal-backdrop").classList.toggle("hidden");
			document.getElementById(modalID).classList.toggle("flex");
			document.getElementById("modal-backdrop").classList.toggle("flex");
		}
	</script>
</x-guest-layout>
