<x-modal>
    <x-slot name="title">
        @lang('Blog Posts')
    </x-slot>

    <x-slot name="content">
        <x-jet-label for="post.title" value="{{ __('Title') }}" />
        <x-jet-input id="post.title" type="text" class="block w-full mt-1" wire:model.defer="post.title" autocomplete="title" />
        <x-jet-input-error for="post.title" class="mt-2" />

        <x-jet-label for="post.body" class="mt-4" value="{{ __('Description') }}" />
        <div wire:ignore>
            <x-easy-mde-editor name="post.body" wire:model.defer="post.body" :options="['hideIcons' => ['side-by-side','fullscreen',]]">
                <x-slot name="script">
                    easyMDE.codemirror.on('change', function () {
                        @this.set('post.body', easyMDE.value())
                    });
                </x-slot>
            </x-easy-mde-editor>
        </div>
        <x-jet-input-error for="post.body" class="mt-2" />

        <x-jet-label for="post.published_at" value="{{ __('Published At') }}" class="mt-4" />
        <x-date-picker id="post.published_at" wire:model="post.published_at" />
        <x-jet-input-error for="post.published_at" class="mt-2" />

        <x-jet-label for="post.active" value="{{ __('Draft') }}?" class="mt-4" />
        <div class="relative inline-block w-full">
            <select class="w-full h-10 pl-3 pr-6 text-base text-gray-800 placeholder-gray-600 border rounded-lg appearance-none focus:shadow-outline" wire:model.defer="post.active">
                <option value="" selected="selected">-- @lang('Please select') --</option>
                <option value="0" selected="selected">@lang('Yes')</option>
                <option value="1">@lang('No')</option>
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-800 pointer-events-none">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
            </div>
        </div>
        <x-jet-input-error for="post.active" class="mt-2" />

        <x-jet-label for="post.sticky" value="{{ __('Sticky') }}?" class="mt-4" />
        <div class="relative inline-block w-full">
            <select class="w-full h-10 pl-3 pr-6 text-base text-gray-800 placeholder-gray-600 border rounded-lg appearance-none focus:shadow-outline" wire:model.defer="post.sticky">
                <option value="" selected="selected">-- @lang('Please select') --</option>
                <option value="0">@lang('No')</option>
                <option value="1">@lang('Yes')</option>
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-800 pointer-events-none">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
            </div>
        </div>
        <x-jet-input-error for="post.sticky" class="mt-2" />

        <x-jet-label for="checked" value="{{ __('Tags') }}" class="mt-4" />
        @foreach($tags as $tag)
            <label class="inline-flex items-center m-2">
                <input class="text-green-600 border-gray-300 rounded shadow-sm focus:border-green-300 focus:ring focus:ring-offset-0 focus:ring-green-200 focus:ring-opacity-50" type="checkbox" value="{{ $tag->name }}" wire:model.defer="checked" />
                <span class="ml-1">{{ $tag->name }}</span>
            </label>
        @endforeach
        <x-jet-input-error for="checked" class="mt-2" />

    </x-slot>

    <x-slot name="buttons">
        <x-jet-button wire:click="save" wire:loading.attr="disabled">
            {{ __('Save') }}
        </x-jet-button>
        <x-jet-secondary-button wire:click="$emit('closeModal')">
            {{ __('Cancel') }}
        </x-jet-secondary-button>
    </x-slot>
</x-modal>
