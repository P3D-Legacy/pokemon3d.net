<div>
    <div wire:loading>
        <svg role="status" class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-green-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
        </svg>
    </div>
    <div wire:init="loadData">
        @if($gamejolt and $gamesave)
            <p class="text-gray-600 dark:text-slate-400 text-sm">{{ trans('Last synced at') }}: {{ $gamesave->updated_at->diffForHumans() }}</p>
            <div x-data="{ activeTab:1, tabs: [
                { id: 1, label: '{{ trans('Party') }}' },
                { id: 2, label: '{{ trans('Pokédex') }} ({{ trans('Caught') }}: {{ $gamesave->getCaughtPokemonCount() }} / {{ trans('Seen') }}: {{ $gamesave->getSeenPokemonCount() }})' },
                { id: 5, label: '{{ trans('In-Game Trophies') }} ({{ $gamejolt->trophies->where('achieved', true)->count() }}/{{
                            $gamejolt->trophies->count() }})' },
                { id: 6, label: '{{ trans('Statistics') }}' },
            ]}">
                <ul class="flex items-center w-full my-4">
                    <template x-for="(tab, tab.id) in tabs" :key="tab.id">
                        <li class="px-4 py-2 text-gray-500 border-b-2 cursor-pointer dark:border-gray-800" :class="activeTab===tab.id ? 'text-green-500 border-green-500 dark:border-green-500' : ''" @click="activeTab = tab.id" x-text="tab.label"></li>
                    </template>
                </ul>
                <div class="flex w-full dark:text-slate-50">
                    <div x-show="activeTab===1" class="w-full">
                        @livewire('profile.game-save.party', ['gamesave' => $gamesave])
                    </div>
                    <div x-show="activeTab===2" class="w-full">
                        @livewire('profile.game-save.pokedex', ['gamesave' => $gamesave])
                    </div>
                    <div x-show="activeTab===5" class="w-full">
                        @livewire('profile.game-save.trophies', ['gamejolt' => $gamejolt])
                    </div>
                    <div x-show="activeTab===6" class="w-full">
                        @livewire('profile.game-save.statistics', ['gamesave' => $gamesave])
                    </div>
                </div>
            </div>
        @else
            <span wire:loading.remove class='text-gray-500 text-sm pt-2'>{{ trans('User has not connected their Game Jolt account yet') }}</span>
        @endif
    </div>
</div>
