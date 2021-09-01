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
        <a href="#" class="w-full flex items-center justify-center px-4 py-3 bg-gamejolt-green border border-transparent rounded-md font-semibold text-sm text-black uppercase tracking-widest hover:bg-opacity-70 focus:outline-none focus:border-green-200 focus:ring focus:ring-green-100 disabled:opacity-25 transition mt-2">
            <img src="{{ asset('img/gamejolt-logo-light-1x.png') }}">
        </a>        
    </x-jet-authentication-card>
</x-guest-layout>
