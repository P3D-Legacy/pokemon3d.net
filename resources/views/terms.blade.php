<x-guest-layout>
    <div class="pt-4 bg-slate-100">
        <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
            <div>
                <a href="{{ route('home') }}">
                    <x-jet-authentication-card-logo />
                </a>
            </div>

            <div class="w-full sm:max-w-2xl mt-6 mb-10 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose">
                {!! $terms !!}
            </div>
        </div>
    </div>
</x-guest-layout>
