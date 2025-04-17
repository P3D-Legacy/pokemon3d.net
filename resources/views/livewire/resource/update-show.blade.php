<x-modal>
    <x-slot name="title">
        {{ $update->title }}
    </x-slot>

    <x-slot name="content">
        <p><span class="text-slate-400">@lang('Latest supported version'):</span> {{ $update->game_version->version }}</p>
        <p><span class="text-slate-400">@lang('Downloads'):</span> {{ $update->downloads }}</p>
        <p><span class="text-slate-400">@lang('Released'):</span> {{ $update->created_at->diffForHumans() }}</p>
        <article class="pt-4 mt-4 prose border-t border-slate-400 dark:prose-invert dark:text-slate-100 prose-a:text-green-600 dark:border-slate-700">
            {!! Str::of($update->description)->markdown() !!}
        </article>
    </x-slot>

    <x-slot name="buttons">
        <button wire:click="download" class="inline-flex items-center px-3 py-2 text-xs font-semibold tracking-widest uppercase transition bg-green-600 border border-green-600 rounded-md shadow-sm text-green-50 hover:bg-green-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            @lang('Download')
        </button>
        <x-secondary-button wire:click="$dispatch('closeModal')">
            @lang('Close')
        </x-secondary-button>
    </x-slot>
</x-modal>
