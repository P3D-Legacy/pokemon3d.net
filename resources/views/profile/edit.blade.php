<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-800 dark:text-slate-200">
            {{ __('Edit Profile') }}
        </h2>
    </x-slot>

    <div>
        <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')
                <x-jet-section-border />
            @endif

            @if(config("services.gamejolt.game_id") && config("services.gamejolt.private_key"))
                @livewire('profile.connect-gamejolt-account')
                <x-jet-section-border />
            @endif

            @if(config("services.discord.client_id") && config("services.discord.client_secret"))
                @livewire('profile.discord-account')
                <x-jet-section-border />
            @endif

            @if(config("services.xenforo.api_key") && config("services.xenforo.api_url"))
                @livewire('profile.xenforo-account')
                <x-jet-section-border />
            @endif

            {{--@if(config("services.twitter.client_id") && config("services.twitter.client_secret"))
                @livewire('profile.twitter-account')
                <x-jet-section-border />
            @endif--}}

            @if(config("services.facebook.client_id") && config("services.facebook.client_secret"))
                @livewire('profile.facebook-account')
                <x-jet-section-border />
            @endif

            @if(config("services.twitch.client_id") && config("services.twitch.client_secret"))
                @livewire('profile.twitch-account')
                <x-jet-section-border />
            @endif

            @livewire('profile.preference')
            <x-jet-section-border />

            @livewire('profile.consent')
            <x-jet-section-border />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>

                <x-jet-section-border />
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>

                <x-jet-section-border />
            @endif

            <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-jet-section-border />

                <div class="mt-10 sm:mt-0">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
