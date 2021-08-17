<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <a href="{{ route('home') }}">
                <x-jet-authentication-card-logo />
            </a>
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-jet-label for="email" value="{{ __('Email or Username') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="text" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-jet-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-green-700 hover:text-gray-900 px-2" href="{{ route('register') }}">
                    {{ __('Need a account?') }}
                </a>

                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 px-2" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-jet-button class="ml-4">
                    {{ __('Log in') }}
                </x-jet-button>
            </div>
        </form>

        <div class="flex items-center justify-center py-4 xl:py-6 text-sm text-gray-400">
            <span class="w-14 border-b border-gray-400"></span>
            <span class="px-2">or with</span>
            <span class="w-14 border-b border-gray-400"></span>
        </div>

        <a href="" class="w-full flex items-center justify-center px-4 py-3 bg-blue-500 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-400 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
            Forum Account
        </a>        
        <a href="" class="w-full flex items-center justify-center px-4 py-3 bg-gamejolt-green border border-transparent rounded-md font-semibold text-sm text-black uppercase tracking-widest hover:bg-opacity-70 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition mt-2">
            <img src="{{ asset('img/gamejolt-logo-light-1x.png') }}">
        </a>        
    </x-jet-authentication-card>
</x-guest-layout>
