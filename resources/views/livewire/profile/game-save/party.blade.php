<div x-cloak>
    <div wire:loading>
        <svg role="status" class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-green-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
        </svg>
    </div>
    <div class="flex flex-col space-y-4" wire:init="loadData">
        @forelse($party as $pokemon)
            <div class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row dark:border-gray-700 dark:bg-gray-800">
                <img class="object-cover w-28 h-auto rounded-lg m-4" src="{{ $pokemon['Image'] }}" alt="{{ $pokemon['Pokemon'] }}">
                <div class="flex flex-col justify-between p-2 leading-normal">
                    <h5 class="mb-1 text-lg font-bold tracking-tight text-gray-900 dark:text-white">
                        @if($pokemon['isShiny'])
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 inline-block text-yellow-300">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                            </svg>
                        @endif
                        @if($pokemon['EggSteps'] > 0)
                                <svg fill="currentColor" class="h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 32 32" xml:space="preserve"><g><path d="M16,32C9.319,32,3.883,26.564,3.883,19.884C3.883,13.252,9.122,0,16,0c6.878,0,12.117,13.252,12.117,19.884   C28.115,26.564,22.68,32,16,32z M16,1.988c-5.336,0-10.129,12.155-10.129,17.896c0,5.585,4.544,10.128,10.129,10.128 s10.129-4.543,10.129-10.128C26.129,14.143,21.336,1.988,16,1.988z"/></g></svg>
                            {{ __('Egg') }}
                        @else
                            {{ (isset($pokemon['NickName']) && $pokemon['NickName'] != '') ? $pokemon['NickName'] : $pokemon['PokemonName'] }} <span class="text-sm text-gray-400 dark:text-gray-600">&middot; {{ $pokemon['PokemonName'] }} #{{ $pokemon['Pokemon'] }}</span>
                        @endif
                    </h5>
                    <div class="text-gray-700 dark:text-gray-400 text-sm">
                        @if($pokemon['EggSteps'] > 0)
                            <p>{{ trans('Egg Steps') }}: {{ $pokemon['EggSteps'] }}</p>
                        @else
                            <p>{{ trans('Level') }}: {{ $pokemon['Level'] }}</p>
                            <p>{{ trans('Experience') }}: {{ $pokemon['Experience'] }}</p>
                            <p>{{ trans('Friendship') }}: {{ $pokemon['Friendship'] }}</p>
                            <p>{{ trans('Nature') }}: {{ $pokemon['Nature'] }}</p>
                            <p>{{ trans('Ability') }}: {{ $pokemon['Ability'] }}</p>
                        @endif
                        <p>{{ trans('Obtained') }}: {{ ucfirst($pokemon['CatchMethod']) .' '. $pokemon['CatchLocation'] }}</p>
                        <p>{{ trans('Catch trainer') }}: {{ $pokemon['CatchTrainer'] }}</p>
                        {{-- print_r($pokemon) --}}
                    </div>
                </div>
            </div>
        @empty
            <span wire:loading.remove>{{ trans('Nothing found') }}</span>
        @endforelse
    </div>
</div>
