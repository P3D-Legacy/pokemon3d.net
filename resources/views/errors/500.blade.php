<x-guest-layout>
    <main class="relative overflow-hidden">
        <div class="container z-10 flex items-center justify-between h-screen px-6 pt-32 mx-auto md:pt-0">
            <div class="container relative flex flex-col-reverse items-center justify-between h-screen px-6 mx-auto bg-white lg:flex-row dark:bg-slate-800 max-h-96 rounded-xl">
                <div class="w-full mb-16 text-center md:mb-8 lg:text-left">
                    <h1 class="mt-12 font-sans text-5xl font-light text-center text-slate-700 lg:text-left lg:text-8xl md:mt-0 dark:text-slate-300">
                        @lang('Server Error')
                    </h1>
                    @if(app()->bound('sentry') && app('sentry')->getLastEventId())
                        <div class="text-slate-400">Error ID: {{ app('sentry')->getLastEventId() }}</div>
                        <script>
                            Sentry.init({ dsn: '{{ config('sentry.dsn') }}' });
                            Sentry.showReportDialog({
                                eventId: '{{ app('sentry')->getLastEventId() }}',
                                user: {
                                    'name': '{{ auth()->user()->name ?? '' }}',
                                    'email': '{{ auth()->user()->email ?? '' }}',
                                }
                            });
                        </script>
                    @endif
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-8 py-4 mt-8 font-extrabold transition duration-150 bg-green-500 border border-green-400 rounded-lg shadow-xl w-76 text-green-50 group-hover:text-green-100 hover:bg-green-600 hover:-translate-y-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        @lang('Go back home')
                    </a>
                </div>
                <div class="relative block w-full max-w-md mx-auto md:mt-0 lg:max-w-2xl">
                    <img src="{{ asset('img/missingno.png') }}" class="w-40 h-40 mx-auto md:h-60 md:w-60 lg:h-80 lg:w-80" />
                </div>
            </div>
        </div>
    </main>
</x-guest-layout>
