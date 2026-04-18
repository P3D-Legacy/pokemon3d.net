<div>
    <form wire:submit.prevent="save">
        <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 text-center">@lang('Rate this resource')</h3>
            
            <div class="flex flex-col items-center justify-center space-y-4">
                <div class="flex space-x-1">
                    <!-- Star 1 -->
                    <button type="button" 
                            wire:click="$set('rating', 1)" 
                            title="Terrible" 
                            class="w-10 h-10 p-1 rounded-sm cursor-pointer focus:outline-none focus:ring-2 focus:ring-yellow-400 transition-colors duration-150 {{ $rating >= 1 ? 'text-yellow-500' : 'text-gray-400' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </button>
                    
                    <!-- Star 2 -->
                    <button type="button" 
                            wire:click="$set('rating', 2)" 
                            title="Bad" 
                            class="w-10 h-10 p-1 rounded-sm cursor-pointer focus:outline-none focus:ring-2 focus:ring-yellow-400 transition-colors duration-150 {{ $rating >= 2 ? 'text-yellow-500' : 'text-gray-400' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </button>
                    
                    <!-- Star 3 -->
                    <button type="button" 
                            wire:click="$set('rating', 3)" 
                            title="Okay" 
                            class="w-10 h-10 p-1 rounded-sm cursor-pointer focus:outline-none focus:ring-2 focus:ring-yellow-400 transition-colors duration-150 {{ $rating >= 3 ? 'text-yellow-500' : 'text-gray-400' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </button>
                    
                    <!-- Star 4 -->
                    <button type="button" 
                            wire:click="$set('rating', 4)" 
                            title="Good" 
                            class="w-10 h-10 p-1 rounded-sm cursor-pointer focus:outline-none focus:ring-2 focus:ring-yellow-400 transition-colors duration-150 {{ $rating >= 4 ? 'text-yellow-500' : 'text-gray-400' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </button>
                    
                    <!-- Star 5 -->
                    <button type="button" 
                            wire:click="$set('rating', 5)" 
                            title="Amazing" 
                            class="w-10 h-10 p-1 rounded-sm cursor-pointer focus:outline-none focus:ring-2 focus:ring-yellow-400 transition-colors duration-150 {{ $rating >= 5 ? 'text-yellow-500' : 'text-gray-400' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </button>
                </div>
                
                <div class="text-center">
                    @if($rating > 0)
                        <p class="text-lg font-medium text-slate-700 dark:text-slate-300">
                            @if($rating == 1) @lang('Terrible')
                            @elseif($rating == 2) @lang('Bad')
                            @elseif($rating == 3) @lang('Okay')
                            @elseif($rating == 4) @lang('Good')
                            @elseif($rating == 5) @lang('Amazing')
                            @endif
                        </p>
                    @else
                        <p class="text-slate-500 dark:text-slate-400">@lang('Click to rate this resource')</p>
                    @endif
                </div>
            </div>
            
            <input type="hidden" wire:model="rating" />
            <x-input-error for="rating" class="mt-2" />
        </div>

        <div class="mb-6">
            <x-label for="body" class="mb-2" value="{{ __('Your review of this resource') }}" />
            <x-text-area id="body" name="body" class="block w-full" placeholder="{{ __('Share your thoughts about this resource...') }}" rows="4" wire:model="body"></x-text-area>
            <div class="flex justify-between mt-1">
                <span class="text-xs text-slate-500 dark:text-slate-400">@lang('Min characters'): 10 &middot; @lang('Max characters'): 255</span>
                <span class="text-xs text-slate-500 dark:text-slate-400" x-text="($wire.body || '').length + '/255'"></span>
            </div>
            <x-input-error for="body" class="mt-2" />
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('resource.uuid', $resource->uuid) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-slate-800 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700">
                {{ __('Cancel') }}
            </a>
            <x-button type="submit" wire:loading.attr="disabled" wire:target="save" :disabled="$isSubmitting">
                <span wire:loading.remove wire:target="save">{{ __('Submit Review') }}</span>
                <span wire:loading wire:target="save">{{ __('Submitting...') }}</span>
            </x-button>
        </div>
    </form>
</div>