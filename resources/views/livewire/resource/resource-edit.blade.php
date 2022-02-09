<x-modal>
    <x-slot name="title">
        Edit Resource
    </x-slot>

    <x-slot name="content">
        <x-jet-label for="name" value="{{ __('Name') }}" />
        <x-jet-input id="name" type="text" class="block w-full mt-1" wire:model.defer="name" autocomplete="name" />
        <x-jet-input-error for="name" class="mt-2" />

        <x-jet-label for="brief" class="mt-4" value="{{ __('brief') }}" />
        <x-jet-input id="brief" type="text" name="brief" class="block w-full mt-1" placeholder="A brief one-line description for My Resource Pack" autofocus wire:model.defer="brief" />
        <x-jet-input-error for="brief" class="mt-2" />

        <x-jet-label for="category" class="mt-4" value="{{ __('Category') }}" />
        <div class="relative inline-block w-full">
            <select class="w-full h-10 pl-3 pr-6 text-base text-gray-800 placeholder-gray-600 border rounded-lg appearance-none focus:shadow-outline" id="category" name="category" wire:model.defer="category">
                <option value="">Select a category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $category->id == $category_id ? 'selected="selected"' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-800 pointer-events-none">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
            </div>
        </div>
        <x-jet-input-error for="category" class="mt-2" />

        <x-jet-label for="description" class="mt-4" value="{{ __('Description') }}" />
        <x-easy-mde name="description" wire:model.defer="description" :options="['hideIcons' => ['side-by-side','fullscreen',]]"></x-easy-mde>
        <x-jet-input-error for="description" class="mt-2" />
        
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