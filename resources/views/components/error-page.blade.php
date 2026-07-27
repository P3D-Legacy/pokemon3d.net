@props([
    'code' => null,
    'title',
    'message' => null,
    'image' => 'img/missingno.png',
    'imageAlt' => 'MissingNo.',
])

<x-guest-layout>
    <main class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden bg-spring bg-top bg-repeat px-4 py-16">
        <div class="pointer-events-none absolute inset-0 bg-black/25" aria-hidden="true"></div>
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-b from-black/30 via-transparent to-black/30" aria-hidden="true"></div>

        @if ($code)
            <div class="pointer-events-none absolute inset-0 flex items-center justify-center select-none" aria-hidden="true">
                <span class="error-page-code text-[clamp(8rem,42vw,18rem)] font-semibold leading-none tracking-tighter text-white/10">
                    {{ $code }}
                </span>
            </div>
        @endif

        <div class="error-page-content relative z-10 flex w-full max-w-lg flex-col items-center gap-8 text-center text-white">
            <a
                href="{{ route('home') }}"
                class="transition hover:opacity-90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white"
            >
                <img
                    src="{{ asset('img/pokemon3d_logo.png') }}"
                    alt="{{ config('app.name', 'Pokémon 3D') }}"
                    class="mx-auto h-12 w-auto drop-shadow-md sm:h-16"
                >
            </a>

            <div class="error-page-sprite">
                <img
                    src="{{ asset($image) }}"
                    alt="{{ $imageAlt }}"
                    width="144"
                    height="144"
                    class="mx-auto size-28 drop-shadow-lg sm:size-36"
                    style="image-rendering: pixelated"
                >
            </div>

            <div class="flex flex-col gap-3">
                <h1 class="text-3xl font-semibold tracking-tight text-balance drop-shadow-md sm:text-4xl lg:text-5xl">
                    {{ $title }}
                </h1>

                @if ($message)
                    <p class="text-base text-pretty text-white/90 drop-shadow sm:text-lg">
                        {{ $message }}
                    </p>
                @endif

                {{ $slot }}
            </div>

            <a
                href="{{ route('home') }}"
                class="inline-flex h-11 items-center justify-center gap-2 border border-transparent bg-primary px-4 text-sm font-medium text-primary-foreground transition-all hover:bg-primary/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ring active:translate-y-px"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                @lang('Go back home')
            </a>
        </div>
    </main>

    <style>
        @keyframes error-page-float {
            0%,
            100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-0.625rem);
            }
        }

        @keyframes error-page-rise {
            from {
                opacity: 0;
                transform: translateY(0.75rem);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes error-page-code-pulse {
            0%,
            100% {
                opacity: 0.1;
            }
            50% {
                opacity: 0.16;
            }
        }

        .error-page-sprite {
            animation: error-page-float 3.2s ease-in-out infinite;
        }

        .error-page-content {
            animation: error-page-rise 0.55s ease-out both;
        }

        .error-page-code {
            animation: error-page-code-pulse 5s ease-in-out infinite;
        }

        @media (prefers-reduced-motion: reduce) {
            .error-page-sprite,
            .error-page-content,
            .error-page-code {
                animation: none;
            }
        }
    </style>
</x-guest-layout>
