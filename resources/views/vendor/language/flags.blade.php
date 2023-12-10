<ul class="{{ config('language.flags.ul_class') }}">
    @php
        $languages = config('app.env') === 'production' ? config('language.done') : config('language.allowed');
    @endphp
    @foreach ($languages as $code)
        <x-dropdown-link href="{{ language()->back($code) }}">
            <img src="{{ asset('img/vendor/language/flags/'. language()->country($code) .'.png') }}" alt="{{ language()->getName($code) }}" width="{{ config('language.flags.width') }}" class="inline-flex mr-2" /> {{ language()->getName($code) }}
        </x-dropdown-link>
    @endforeach
    <x-dropdown-link href="{{ config('app.lang_contribution_url') ?? '#' }}">
        <span class="text-xs">{{ __('Contribute your language') }}!</span>
    </x-dropdown-link>
</ul>
