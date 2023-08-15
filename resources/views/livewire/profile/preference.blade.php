<x-jet-action-section>
    <x-slot name="title">
        {{ __('Preferences') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Here you can choose what to share on your public profile.') }}
    </x-slot>

    <x-slot name="content">
        @foreach($settings as $setting => $value)
            <div class="flex items-center my-2">
                <button type="button"
                    class="{{ ($value) ? 'bg-green-800 dark:bg-green-600' : 'bg-slate-200 dark:bg-slate-300' }} relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-800"
                    aria-pressed="false"
                    wire:click="toggle('{{$setting}}')"
                >
                    <span class="sr-only">Use setting</span>
                    <span aria-hidden="true" class="{{ ($value) ? 'translate-x-5' : 'translate-x-0' }} pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow ring-0 transition ease-in-out duration-200"></span>
                </button>
                <span class="ml-3">
                    <span class="text-sm font-medium text-slate-900 capitalize dark:text-slate-200">Show {!! $setting !!}</span>
                </span>
            </div>
        @endforeach
    </x-slot>

</x-jet-action-section>
