<button class="px-2 py-1 items-center align-middle transition-colors duration-150 rounded-md focus:shadow-outline cursor-pointer {{ ($liked ? 'text-red-50 bg-red-500 dark:bg-red-600' : 'text-slate-600 bg-slate-200 dark:bg-slate-400 dark:text-slate-600') }}" wire:click="like">
    @if($liked)
        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
        </svg>
    @else
        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
    @endif
    {{ App\Helpers\NumberHelper::nearestK($count) }}
</button>
