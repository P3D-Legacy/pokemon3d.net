<a class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 w-full sm:w-1/4 flex flex-col flex-grow flex-shrink" href="{{ $url }}">
    <p class="text-gray-600 dark:text-white">
        <span class="font-bold text-green-500 text-lg">
            “
        </span>
        {{ $title }}
        <span class="font-bold text-green-500 text-lg">
            ”
        </span>
    </p>
    <div class="flex items-center mt-4">
        <div class="flex flex-col ml-2 justify-between">
            <span class="font-semibold text-green-500 text-sm">
                {{ $author }}
            </span>
            <span class="text-gray-600 dark:text-gray-400 text-xs flex items-center">
                {{ Carbon\Carbon::parse($date)->format('F j, Y') }}
            </span>
        </div>
    </div>
</a>