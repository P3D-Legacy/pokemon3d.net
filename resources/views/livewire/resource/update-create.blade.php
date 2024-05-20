<x-modal>
    <x-slot name="title">
        @lang('Add resource update')
    </x-slot>

    <x-slot name="content">
        <x-label for="version" value="{{ __('Version Title') }}" />
        <x-input id="version" type="text" class="block w-full mt-1" wire:model="version" autocomplete="version" placeholder="1.2.3" />
        <x-input-error for="version" class="mt-2" />

        <x-label for="gameversion" class="mt-4" value="{{ __('Latest supported version') }}" />
        <div class="relative inline-block w-full">
            <select class="w-full h-10 pl-3 pr-6 text-base text-slate-800 placeholder-slate-600 border rounded-lg appearance-none focus:shadow-outline" id="gameversion" name="gameversion" wire:model="gameversion">
                <option value="">@lang('Select a game version')</option>
                @foreach ($gameversions as $game_version)
                    <option value="{{ $game_version->id }}" {{ $game_version->id == $gameversion ? 'selected="selected"' : '' }}>{{ $game_version->version }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center px-2 text-slate-800 pointer-events-none">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
            </div>
        </div>
        <x-input-error for="gameversion" class="mt-2" />

        <x-label for="description" class="mt-4" value="{{ __('Description') }}" />
        <div wire:ignore>
            <x-easy-mde-editor name="description" id="easyMDE" :options="['hideIcons' => ['side-by-side','fullscreen',]]">
                <x-slot name="script">
                    easyMDE.codemirror.on('change', function () {
                        @this.set('description', easyMDE.value())
                    });
                </x-slot>
            </x-easy-mde-editor>
        </div>
        <x-input-error for="description" class="mt-2" />

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
        <x-input-error for="file" class="mt-2" />
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
