<x-guest-layout>
	<x-jet-authentication-card>
		<x-slot name="logo">
			<a href="{{ route('home') }}">
				<x-jet-authentication-card-logo />
			</a>
		</x-slot>

		@if(Session::has('error'))
			<div class="relative px-4 py-3 mb-2 leading-normal text-red-700 bg-red-100 rounded-lg" role="alert">
				<svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
				</svg>
				{{ Session::get('error') }}
			</div>
		@endif

		@if(\Carbon\Carbon::createFromFormat('Y-m-d', '2022-02-01')->isFuture())
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
		<a href="{{ route('discord.login') }}" class="flex items-center justify-center w-full px-4 py-2 mt-2 text-sm font-semibold tracking-widest text-white uppercase transition bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 active:bg-indigo-400 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25">
			<svg class="w-auto h-6" viewBox="0 0 292 80" fill="none" xmlns="http://www.w3.org/2000/svg">
				<g clip-path="url(#clip0)">
					<path d="M61.7958 16.494C57.0736 14.2846 52.0244 12.6789 46.7456 11.7646C46.0973 12.9367 45.3399 14.5132 44.8177 15.7673C39.2062 14.9234 33.6463 14.9234 28.138 15.7673C27.6159 14.5132 26.8413 12.9367 26.1872 11.7646C20.9027 12.6789 15.8477 14.2905 11.1255 16.5057C1.60078 30.8988 -0.981215 44.9344 0.309785 58.7707C6.62708 63.4883 12.7493 66.3541 18.7682 68.2294C20.2543 66.1841 21.5797 64.0099 22.7215 61.7185C20.5469 60.8922 18.4641 59.8725 16.4961 58.6887C17.0182 58.3019 17.5289 57.8975 18.0223 57.4814C30.0257 63.0957 43.0677 63.0957 54.9277 57.4814C55.4269 57.8975 55.9375 58.3019 56.4539 58.6887C54.4801 59.8783 52.3916 60.898 50.217 61.7244C51.3588 64.0099 52.6785 66.19 54.1703 68.2352C60.195 66.3599 66.3229 63.4942 72.6402 58.7707C74.155 42.7309 70.0525 28.8242 61.7958 16.494ZM24.3568 50.2615C20.7535 50.2615 17.7985 46.8976 17.7985 42.8012C17.7985 38.7048 20.6904 35.3351 24.3568 35.3351C28.0233 35.3351 30.9782 38.6989 30.9151 42.8012C30.9208 46.8976 28.0233 50.2615 24.3568 50.2615ZM48.5932 50.2615C44.9899 50.2615 42.0349 46.8976 42.0349 42.8012C42.0349 38.7048 44.9267 35.3351 48.5932 35.3351C52.2596 35.3351 55.2146 38.6989 55.1515 42.8012C55.1515 46.8976 52.2596 50.2615 48.5932 50.2615Z" fill="currentColor"/>
					<path d="M98.0293 26.1707H113.693C117.469 26.1707 120.659 26.7743 123.276 27.9757C125.886 29.177 127.843 30.8531 129.14 32.998C130.436 35.1429 131.09 37.5984 131.09 40.3645C131.09 43.072 130.413 45.5275 129.059 47.7251C127.705 49.9286 125.645 51.6692 122.874 52.9526C120.103 54.236 116.671 54.8806 112.569 54.8806H98.0293V26.1707ZM112.408 47.5845C114.95 47.5845 116.907 46.934 118.272 45.6388C119.638 44.3378 120.321 42.568 120.321 40.3235C120.321 38.243 119.712 36.5845 118.496 35.3421C117.28 34.0997 115.438 33.4727 112.976 33.4727H108.076V47.5845H112.408Z" fill="currentColor"/>
					<path d="M154.541 54.8456C152.372 54.2713 150.415 53.4391 148.677 52.3432V45.5335C149.991 46.5707 151.752 47.4264 153.961 48.1003C156.17 48.7684 158.305 49.1024 160.37 49.1024C161.334 49.1024 162.063 48.9735 162.556 48.7156C163.05 48.4578 163.297 48.1472 163.297 47.7897C163.297 47.3795 163.165 47.0396 162.895 46.7641C162.625 46.4887 162.103 46.2601 161.329 46.0667L156.509 44.9591C153.749 44.3028 151.792 43.3944 150.628 42.2282C149.463 41.0678 148.883 39.5441 148.883 37.6571C148.883 36.0689 149.388 34.6918 150.41 33.5138C151.425 32.3359 152.871 31.4275 154.747 30.7887C156.624 30.1441 158.815 29.8218 161.334 29.8218C163.583 29.8218 165.643 30.0679 167.52 30.5602C169.396 31.0525 170.945 31.6795 172.179 32.4472V38.8878C170.916 38.1201 169.47 37.5165 167.818 37.0593C166.171 36.6081 164.479 36.3854 162.734 36.3854C160.215 36.3854 158.959 36.8249 158.959 37.6981C158.959 38.1084 159.154 38.4131 159.544 38.6182C159.934 38.8233 160.651 39.0343 161.69 39.257L165.706 39.9954C168.329 40.4584 170.285 41.273 171.57 42.4333C172.856 43.5937 173.498 45.3108 173.498 47.5846C173.498 50.0752 172.437 52.0502 170.308 53.5153C168.179 54.9804 165.161 55.7129 161.248 55.7129C158.947 55.7071 156.71 55.4199 154.541 54.8456Z" fill="currentColor"/>
					<path d="M182.978 53.9839C180.678 52.8352 178.939 51.2764 177.78 49.3073C176.621 47.3382 176.036 45.123 176.036 42.6616C176.036 40.2003 176.638 37.9968 177.843 36.057C179.048 34.1172 180.815 32.5935 183.145 31.4859C185.474 30.3783 188.257 29.8274 191.499 29.8274C195.515 29.8274 198.849 30.6889 201.5 32.4118V39.919C200.565 39.2626 199.474 38.7293 198.229 38.3191C196.984 37.9089 195.653 37.7037 194.23 37.7037C191.74 37.7037 189.795 38.1667 188.389 39.0985C186.983 40.0303 186.278 41.2434 186.278 42.7495C186.278 44.2263 186.96 45.4336 188.326 46.383C189.692 47.3265 191.671 47.8012 194.27 47.8012C195.607 47.8012 196.927 47.6019 198.229 47.2093C199.526 46.8108 200.645 46.3244 201.58 45.75V53.011C198.637 54.816 195.223 55.7185 191.338 55.7185C188.068 55.7068 185.279 55.1325 182.978 53.9839Z" fill="currentColor"/>
					<path d="M211.518 53.9841C209.2 52.8355 207.433 51.2649 206.216 49.2665C205 47.2681 204.386 45.0412 204.386 42.5798C204.386 40.1185 204.994 37.9208 206.216 35.9928C207.438 34.0647 209.194 32.5527 211.501 31.4568C213.801 30.3609 216.55 29.8159 219.734 29.8159C222.919 29.8159 225.667 30.3609 227.968 31.4568C230.269 32.5527 232.025 34.053 233.23 35.9693C234.435 37.8857 235.037 40.0833 235.037 42.574C235.037 45.0353 234.435 47.2623 233.23 49.2606C232.025 51.259 230.263 52.8296 227.945 53.9783C225.627 55.1269 222.89 55.7012 219.729 55.7012C216.567 55.7012 213.83 55.1327 211.518 53.9841ZM223.722 46.7055C224.698 45.7093 225.191 44.3907 225.191 42.7498C225.191 41.1089 224.703 39.802 223.722 38.835C222.747 37.8622 221.415 37.3758 219.729 37.3758C218.013 37.3758 216.67 37.8622 215.689 38.835C214.714 39.8079 214.226 41.1089 214.226 42.7498C214.226 44.3907 214.714 45.7093 215.689 46.7055C216.665 47.7018 218.013 48.2058 219.729 48.2058C221.415 48.1999 222.747 47.7018 223.722 46.7055Z" fill="currentColor"/>
					<path d="M259.17 31.3395V40.2004C258.149 39.5147 256.829 39.1748 255.194 39.1748C253.053 39.1748 251.401 39.8371 250.253 41.1615C249.1 42.486 248.526 44.5488 248.526 47.3383V54.8865H238.686V30.8883H248.326V38.5185C248.859 35.7289 249.726 33.672 250.919 32.3416C252.107 31.0172 253.644 30.355 255.515 30.355C256.932 30.355 258.149 30.6832 259.17 31.3395Z" fill="currentColor"/>
					<path d="M291.864 25.3503V54.8866H282.023V49.5127C281.191 51.5345 279.929 53.0758 278.231 54.1306C276.532 55.1797 274.432 55.7071 271.942 55.7071C269.716 55.7071 267.777 55.1562 266.118 54.0486C264.46 52.941 263.181 51.4232 262.28 49.4951C261.385 47.567 260.931 45.387 260.931 42.9491C260.903 40.435 261.379 38.1787 262.36 36.1803C263.336 34.1819 264.718 32.6231 266.497 31.5037C268.276 30.3844 270.307 29.8218 272.585 29.8218C277.273 29.8218 280.417 31.9022 282.023 36.0572V25.3503H291.864ZM280.555 46.5415C281.559 45.5452 282.058 44.2501 282.058 42.6678C282.058 41.1382 281.57 39.8899 280.595 38.9347C279.619 37.9795 278.282 37.4989 276.601 37.4989C274.943 37.4989 273.618 37.9853 272.625 38.9581C271.632 39.931 271.139 41.1909 271.139 42.7498C271.139 44.3087 271.632 45.5804 272.625 46.5649C273.618 47.5494 274.926 48.0417 276.561 48.0417C278.219 48.0359 279.55 47.5377 280.555 46.5415Z" fill="currentColor"/>
					<path d="M139.382 33.4432C142.091 33.4432 144.288 31.4281 144.288 28.9424C144.288 26.4567 142.091 24.4417 139.382 24.4417C136.672 24.4417 134.476 26.4567 134.476 28.9424C134.476 31.4281 136.672 33.4432 139.382 33.4432Z" fill="currentColor"/>
					<path d="M134.472 36.5435C137.478 37.8679 141.208 37.9265 144.283 36.5435V55.0154H134.472V36.5435Z" fill="currentColor"/>
				</g>
				<defs>
					<clipPath id="clip0">
						<rect width="292" height="56.4706" fill="white" transform="translate(0 11.7646)"/>
					</clipPath>
				</defs>
			</svg>
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
