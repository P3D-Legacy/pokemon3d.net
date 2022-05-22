@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block pl-3 pr-4 py-2 border-l-4 border-green-400 text-base font-medium text-green-700 bg-green-50 dark:bg-green-50/80 focus:outline-none focus:text-green-800 focus:bg-green-100 focus:border-green-700 transition'
            : 'block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 dark:hover:bg-gray-700 dark:hover:border-gray-600 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
