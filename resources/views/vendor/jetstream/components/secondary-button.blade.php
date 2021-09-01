<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-green-300 focus:ring focus:ring-green-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition dark:bg-gray-900 dark:text-white dark:border-gray-800 dark:hover:text-gray-300 dark:active:bg-gray-900']) }}>
    {{ $slot }}
</button>
