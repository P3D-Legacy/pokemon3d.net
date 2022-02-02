<x-modal>
    <x-slot name="title">
        Add resource update
    </x-slot>

    <x-slot name="content">
        <x-jet-label for="version" value="{{ __('Version') }}" />
        <x-jet-input id="version" type="text" class="block w-full mt-1" wire:model.defer="version" autocomplete="version" placeholder="1.2.3" />
        <x-jet-input-error for="version" class="mt-2" />

        <x-jet-label for="description" class="mt-4" value="{{ __('Description') }}" />
        <x-easy-mde name="description" wire:model.defer="description" :options="['hideIcons' => ['side-by-side','fullscreen',]]"></x-easy-mde>
        <x-jet-input-error for="description" class="mt-2" />

        <div wire:ignore
             x-data
             x-init="() => {
                FilePond.setOptions({
                    server: {
                        process:(fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                            @this.upload('file', file, load, error, progress)
                        },
                        revert: (filename, load) => {
                            @this.removeUpload('file', filename, load)
                        }
                    },
                    allowFileTypeValidation: true
                });
                FilePond.registerPlugin(FilePondPluginFileValidateType);
                FilePond.create($refs.input, {
                    acceptedFileTypes: ['application/zip', 'application/x-zip-compressed', 'multipart/x-zip']
                });
            }">
            <input type="file" x-ref="input">
        </div>
        <x-jet-input-error for="file" class="mt-2" />
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