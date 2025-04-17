<x-modal>
    <x-slot name="title">
        @lang('Resource')
    </x-slot>

    <x-slot name="content">
        <x-label for="resource.name" value="{{ __('Name') }}" />
        <x-input id="resource.name" type="text" class="block w-full mt-1" wire:model="resource.name" autocomplete="name" />
        <x-input-error for="resource.name" class="mt-2" />

        <x-label for="resource.brief" class="mt-4" value="{{ __('Brief') }}" />
        <x-input id="resource.brief" type="text" name="resource.brief" class="block w-full mt-1" placeholder="A brief one-line description for My Resource Pack" autofocus wire:model="resource.brief" />
        <x-input-error for="resource.brief" class="mt-2" />

        <x-label for="category" class="mt-4" value="{{ __('Category') }}" />
        <div class="relative inline-block w-full">
            <select class="w-full h-10 pl-3 pr-6 text-base text-slate-800 placeholder-slate-600 border rounded-lg appearance-none focus:shadow-outline" id="category" name="category" wire:model="category">
                <option value="">@lang('Select a category')</option>
                @foreach ($categories as $c)
                    <option value="{{ $c->id }}" {{ $c->id == $category ? 'selected="selected"' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center px-2 text-slate-800 pointer-events-none">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
            </div>
        </div>
        <x-input-error for="category" class="mt-2" />

        <x-label for="resource.description" class="mt-4" value="{{ __('Description') }}" />
        <div wire:ignore>
            <x-easy-mde-editor name="resource.description" wire:model="resource.description" :options="['hideIcons' => ['side-by-side','fullscreen',]]">
                <x-slot name="script">
                    easyMDE.codemirror.on('change', function () {
                        @this.set('resource.description', easyMDE.value())
                    });
                </x-slot>
            </x-easy-mde-editor>
        </div>
        <x-input-error for="resource.description" class="mt-2" />

    </x-slot>

    <x-slot name="buttons">
        <x-button wire:click="save" wire:loading.attr="disabled">
            {{ __('Save') }}
        </x-button>
        <x-secondary-button wire:click="$dispatch('closeModal')">
            {{ __('Cancel') }}
        </x-secondary-button>
    </x-slot>
</x-modal>
