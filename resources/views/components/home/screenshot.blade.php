<div class="relative float-left w-full carousel-item {{ $active ? 'active' : '' }}">
    <img src="{{ asset($path) }}" class="block object-cover" alt="" />
    {{--
    <div class="absolute hidden text-center carousel-caption md:block">
        <h5 class="text-xl drop-shadow shadow-black dark:shadow-white">{{ $title }}</h5>
        <p>@lang('Screenshot by'): {{ $author }}</p>
    </div>
    --}}
</div>