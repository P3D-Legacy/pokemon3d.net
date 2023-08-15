<a class="flex flex-col flex-grow flex-shrink w-full p-4 bg-white shadow-lg dark:bg-slate-800 rounded-xl sm:w-1/4" href="{{ $url }}">
    <p class="text-slate-800 dark:text-white">
        <span class="text-lg font-bold text-green-700">
            “
        </span>
        {{ $title }}
        <span class="text-lg font-bold text-green-700">
            ”
        </span>
    </p>
    <div class="flex items-center mt-4">
        <div class="flex flex-col justify-between ml-2">
            <span class="text-sm font-semibold text-green-700 font-bold">
                {{ $author }}
            </span>
            <span class="flex items-center text-xs text-slate-800 dark:text-slate-400">
                {{ Carbon\Carbon::parse($date)->isoFormat('LL') }}
            </span>
        </div>
    </div>
</a>
