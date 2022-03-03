<ul class="{{ config('language.flags.ul_class') }}">
    @foreach (language()->allowed() as $code => $name)
        <x-jet-dropdown-link href="{{ language()->back($code) }}">
            <img src="{{ asset('img/vendor/language/flags/'. language()->country($code) .'.png') }}" alt="{{ $name }}" width="{{ config('language.flags.width') }}" class="inline-flex mr-2" /> {{ $name }}
        </x-jet-dropdown-link>
    @endforeach
</ul>