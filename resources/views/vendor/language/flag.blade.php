<div class="inline-flex items-center py-3 px-2 text-slate-500 transition bg-transparent border border-transparent rounded-full hover:bg-slate-100 hover:text-slate-700 dark:text-slate-300 dark:hover:bg-slate-800 cursor-pointer">
    <img src="{{ asset('img/vendor/language/flags/'. language()->country(app()->getLocale()) .'.png') }}" alt="{{ language()->country(app()->getLocale()) }}" class="{{ config('language.flags.img_class') }}" />
</div>
