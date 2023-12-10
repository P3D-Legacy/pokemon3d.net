<span class="flex items-center w-full mt-12 text-3xl align-center">
    <button wire:click="like" wire:loading.attr="disabled" class="flex mx-auto {{ $liked ? 'text-green-500 hover:text-green-400' : 'text-slate-400 hover:text-green-300' }} focus:outline-none focus:ring-0 disabled:text-slate-700">
        @if($liked)
            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-8 h-8" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
            </svg>
        @else
            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
        @endif
        <span class="ml-1 font-medium">{{ App\Helpers\NumberHelper::nearestK($count) }}</span>
        <span class="sr-only">likes</span>
    </button>
</span>
