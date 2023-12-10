@props(['formAction' => false])

<div class="text-slate-900 bg-white dark:text-slate-100 dark:bg-slate-800">
    @if($formAction)
        <form wire:submit.prevent="{{ $formAction }}">
        @csrf
    @endif
        <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-700 sm:px-6 sm:py-4">
            @if(isset($title))
                <h3 class="text-lg font-medium leading-6">
                    {{ $title }}
                </h3>
            @endif
        </div>
        <div class="px-4 py-2 sm:p-6">
            {{ $content }}
        </div>

        <div class="justify-end px-4 py-2 space-x-2 border-t border-slate-100 dark:border-slate-700 sm:p-4 sm:flex">
            {{ $buttons }}
        </div>
    @if($formAction)
        </form>
    @endif
</div>
