<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-md font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:text-slate-500 focus:outline-none focus:border-green-300 focus:ring focus:ring-green-200 active:text-slate-800 active:bg-slate-50 disabled:opacity-25 transition dark:bg-slate-900 dark:text-white dark:border-slate-800 dark:hover:text-slate-300 dark:active:bg-slate-900']) }}>
    {{ $slot }}
</button>
