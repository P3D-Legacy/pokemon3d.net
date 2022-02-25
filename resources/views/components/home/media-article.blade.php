<a class="flex flex-col flex-grow flex-shrink w-full p-4 bg-white shadow-lg dark:bg-gray-800 rounded-xl sm:w-1/4" href="{{ $url }}">
    <p class="text-gray-600 dark:text-white">
        <span class="text-lg font-bold text-green-500">
            “
        </span>
        {{ $title }}
        <span class="text-lg font-bold text-green-500">
            ”
        </span>
    </p>
    <div class="flex items-center mt-4">
        <div class="flex flex-col justify-between ml-2">
            <span class="text-sm font-semibold text-green-500">
                {{ $author }}
            </span>
                {{ Carbon\Carbon::parse($date)->format('F j, Y') }}
            <span class="flex items-center text-xs text-gray-600 dark:text-gray-400">
            </span>
        </div>
    </div>
</a>