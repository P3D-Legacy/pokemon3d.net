<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-repeat bg-top bg-spring">
    <div>
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg dark:bg-slate-800">
        {{ $slot }}
    </div>
</div>
