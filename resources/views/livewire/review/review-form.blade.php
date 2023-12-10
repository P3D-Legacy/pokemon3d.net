<x-modal>
    <x-slot name="title">
        @lang('Leave a review for the game')
    </x-slot>

    <x-slot name="content">
        <div x-data="
        {
            rating: 0,
            hoverRating: 0,
            ratings: [{'amount': 1, 'label':'@lang('Terrible')'}, {'amount': 2, 'label':'@lang('Bad')'}, {'amount': 3, 'label':'@lang('Okay')'}, {'amount': 4, 'label':'@lang('Good')'}, {'amount': 5, 'label':'@lang('Amazing')'}],
            rate(amount) {
                this.rating = amount;
            },
            currentLabel() {
                let r = this.rating;
                if (this.hoverRating != this.rating) r = this.hoverRating;
                let i = this.ratings.findIndex(e => e.amount == r);
                if (i >=0) {return this.ratings[i].label;} else {return ''};
            }
        }
        ">
            <div class="flex flex-col items-center justify-center p-3 m-2 mx-auto space-y-2">
                <div class="flex space-x-0">
                    <template x-for="(star, index) in ratings" :key="index">
                        <button x-on:click="$wire.set('rating', star.amount)" @click="rate(star.amount)" @mouseover="hoverRating = star.amount" @mouseleave="hoverRating = rating" aria-hidden="true" :title="star.label" class="w-12 p-1 m-0 text-slate-400 rounded-sm cursor-pointer fill-current focus:outline-none focus:shadow-outline" :class="{'text-slate-600': hoverRating >= star.amount, 'text-yellow-400': rating >= star.amount && hoverRating >= star.amount}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="transition duration-150 w-15" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </button>
                    </template>
                </div>
                <div class="p-2">
                    <template x-if="rating || hoverRating">
                        <p x-text="currentLabel()"></p>
                    </template>
                    <template x-if="!rating && !hoverRating">
                        <p>@lang('Please click star rating!')</p>
                    </template>
                </div>
            </div>
            <input class="hidden" type="number" id="rating" wire:model.defer="rating" />
            <x-jet-input-error for="rating" class="mt-2" />
        </div>

        <x-jet-label for="gameversion" class="mt-4" value="{{ __('Game version reviewed') }}" />
        <div class="relative inline-block w-full">
            <select class="w-full h-10 pl-3 pr-6 text-base text-slate-800 placeholder-slate-600 border rounded-lg appearance-none focus:shadow-outline dark:bg-black dark:text-slate-400 dark:border-slate-800" id="gameversion" name="gameversion" wire:model.defer="gameversion">
                <option value="">@lang('Select a game version')</option>
                @foreach ($gameversions as $game_version)
                    <option value="{{ $game_version->id }}" {{ $game_version->id == $gameversion ? 'selected="selected"' : '' }}>{{ $game_version->version }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center px-2 text-slate-800 pointer-events-none">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
            </div>
        </div>
        <x-jet-input-error for="gameversion" class="mt-2" />

        <x-jet-label for="body" class="mt-4" value="{{ __('Your review of this game') }}" />
        <x-text-area id="body" name="body" class="block w-full mt-1" placeholder="{{ __('Your review of this game') }}" autofocus wire:model.defer="body"></x-text-area>
        <span class="text-xs text-slate-400">@lang('Min characters'): 10 &middot; @lang('Max characters'): 255</span>
        <x-jet-input-error for="body" class="mt-2" />
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
