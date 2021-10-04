@props(['align' => 'bottom', 'width' => '48', 'contentClasses' => 'py-1 bg-white', 'dropdownClasses' => '', 'active' => false])

@php
switch ($align) {
    case 'left':
        $alignmentClasses = 'origin-top-left left-0';
        break;
    case 'top':
        $alignmentClasses = 'origin-top';
        break;
    case 'none':
    case 'false':
        $alignmentClasses = '';
        break;
    case 'bottom':
        $alignmentClasses = 'top-14';
        break;
    case 'right':
    default:
        $alignmentClasses = 'origin-top-right right-0';
        break;
}

switch ($width) {
    case '48':
        $width = 'w-48';
        break;
}

$classes = ($active ?? false)
            ? 'cursor-pointer relative inline-flex items-center px-1 pt-1 border-b-2 border-green-600 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-800 transition'
            : 'cursor-pointer relative inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition';

@endphp

<div x-data="{ open: false }" @click.away="open = false" @close.stop="open = false" {{ $attributes->merge(['class' => $classes]) }}>
    <div @click="open = ! open" class="inline-flex items-center h-full">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute z-50 mt-2 {{ $width }} rounded-md shadow-lg {{ $alignmentClasses }} {{ $dropdownClasses }}"
            style="display: none;"
            @click="open = false">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
