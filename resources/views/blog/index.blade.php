<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            @lang('Blog')
        </h2>
    </x-slot>
    <div>
        <div class="px-4 py-10 mx-auto max-w-7xl md:px-6 lg:px-8 sm:pb-16">
            @component('components.breadcrumb', ['breadcrumbs' => [
                ['url' => null, 'label' => __('Blog')],
            ]])
            @endcomponent
            <div class="pt-5 pb-10">
                <h2 class="text-3xl font-bold leading-7 text-white sm:truncate sm:text-3xl sm:tracking-tight">
                    @lang('Official Blog')
                </h2>
                <h4 class="mt-1 text-sm text-gray-300">
                    @lang('This is the official blog of the team and developers of the game.')
                </h4>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-5 sm:gap-4">
                @foreach ($posts as $post)
                    <x-home.article :post="$post" />
                @endforeach
            </div>
            @if($posts->hasPages())
                <div class="p-4 bg-white rounded-lg dark:bg-gray-800">
                    {!! $posts->onEachSide(1)->links() !!}
                </div>
            @endif
            @if($posts->isEmpty())
                <div class="w-full text-xs text-center">
                    <p class="mb-1 dark:text-gray-400">{{ __('There is nothing to show') }}...</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
