<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __("Update your account's profile information and email address.") }}<br>
        {{ __('Your timezone') }}: {{ $this->user->timezone }}<br>
        <br>
        {{ __('Your user was created at') }}:<br>
        UTC: {{ $this->user->created_at->setTimezone('UTC')->format('Y-m-d H:i:s') }}<br>
        {{ $this->user->timezone }}: {{ $this->user->created_at->setTimezone($this->user->timezone)->format('Y-m-d H:i:s') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" class="hidden"
                            wire:model="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-label for="photo" value="{{ __('Photo') }}" />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="object-cover w-20 h-20 rounded-full">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview">
                    <span class="block w-20 h-20 rounded-full"
                          x-bind:style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-secondary-button class="mt-2 mr-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
                </x-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </x-secondary-button>
                @endif

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Name') }}" />
            <x-input id="name" type="text" class="block w-full mt-1" wire:model.defer="state.name" autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="username" value="{{ __('Username') }}" />
            <x-input id="username" type="text" class="block w-full mt-1 bg-slate-200" wire:model.defer="state.username" disabled />
            <x-input-error for="username" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="block w-full mt-1" wire:model.defer="state.email" />
            <x-input-error for="email" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="birthdate" value="{{ __('Birthdate') }}" />
            <x-input id="birthdate" type="text" class="block w-full mt-1 flatpickrBirthdate" wire:model.defer="state.birthdate" />
            <x-input-error for="birthdate" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="gender" value="{{ __('Gender') }}" />
            <select id="gender" class="form-select appearance-none block w-full px-3 py-1.5 border-slate-300 focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50 rounded-md shadow-sm disabled:opacity-50 disabled:cursor-not-allowed dark:bg-black dark:text-white dark:border-slate-900 mt-1" wire:model.defer="state.gender">
                <option value="0" {{ $this->user->gender == 0 ? 'selected' : '' }}>No selection</option>
                <option value="1" {{ $this->user->gender == 1 ? 'selected' : '' }}>Male</option>
                <option value="2" {{ $this->user->gender == 2 ? 'selected' : '' }}>Female</option>
                <option value="3" {{ $this->user->gender == 3 ? 'selected' : '' }}>Genderless</option>
            </select>
            <x-input-error for="gender" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="location" value="{{ __('Location') }}" />
            <x-input id="location" type="text" class="block w-full mt-1" wire:model.defer="state.location" />
            <x-input-error for="location" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="about" value="{{ __('About') }}" />
            <x-text-area id="about" class="block w-full mt-1" wire:model.defer="state.about" />
            <x-input-error for="about" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="mr-3 text-green-500" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>
