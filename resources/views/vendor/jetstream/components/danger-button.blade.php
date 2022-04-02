<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-600 disabled:opacity-25 transition dark:bg-red-700 dark:hover:bg-red-600 dark:focus:border-red-500 dark:focus:ring-red-400 active:bg-red-600']) }}>
    {{ $slot }}
</button>
