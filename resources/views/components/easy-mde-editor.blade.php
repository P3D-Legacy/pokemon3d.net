<textarea
    x-data
    x-init="(function(easyMDE, $el) { {{ $script ?? '' }} ; return easyMDE; })(new EasyMDE({ element: $el {{ $jsonOptions() }} }), $el)"
    name="{{ $name }}"
    id="{{ $id }}"
    {{ $attributes }}
>{{ old($name, $slot) }}</textarea>