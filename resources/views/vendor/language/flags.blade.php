<ul class="{{ config('language.flags.ul_class') }}">
    @foreach (language()->allowed() as $code => $name)
        <x-jet-dropdown-link href="{{ language()->back($code) }}">
            <img src="{{ asset('img/vendor/language/flags/'. language()->country($code) .'.png') }}" alt="{{ $name }}" width="{{ config('language.flags.width') }}" class="inline-flex mr-2" /> {{ $name }}
        </x-jet-dropdown-link>
    @endforeach
    <x-jet-dropdown-link href="{{ config('app.lang_contribution_url') }}">
        <span class="text-xs">{{ __('Contribute your language') }}!</span>
    </x-jet-dropdown-link>
</ul>
