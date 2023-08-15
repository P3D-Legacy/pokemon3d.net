<x-app-layout>
    <div class="px-4 py-10 mx-auto max-w-7xl md:px-6 lg:px-8 sm:pb-16">
        @component('components.breadcrumb', ['breadcrumbs' => [
            ['url' => null, 'label' => __('Blog')],
        ]])
        @endcomponent
        <div class="pt-5 pb-10">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold leading-7 sm:truncate sm:tracking-tight text-slate-800 dark:text-slate-200">
                @lang('Official Blog')
            </h2>
            <h4 class="mt-1 text-sm text-slate-700 dark:text-slate-400">
                @lang('This is the official blog of the team and developers of the game.')
            </h4>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-5 sm:gap-4">
            @foreach ($posts as $post)
                <x-home.article :post="$post" />
            @endforeach
        </div>
        @if($posts->hasPages())
            <div class="p-4 bg-white rounded-lg dark:bg-slate-800">
                {!! $posts->onEachSide(1)->links() !!}
            </div>
        @endif
        @if($posts->isEmpty())
            <div class="w-full text-xs text-center">
                <p class="mb-1 dark:text-slate-400">{{ __('There is nothing to show') }}...</p>
            </div>
        @endif
    </div>
</x-app-layout>
