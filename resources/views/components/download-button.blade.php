<div class="inline-block mt-2 md:mt-6">
    <a href="{{ App\Models\GameVersion::latest()->download_url }}" class="inline-flex items-center justify-center px-8 py-4 mx-auto font-extrabold transition duration-150 bg-green-500 border border-green-400 rounded-lg shadow-2xl lg:mx-0 w-76 text-green-50 group-hover:text-green-100 hover:bg-green-600 hover:-translate-y-1 shadow-black/50">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 mr-3 text-opacity-50 transform">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
        </svg>
        <span>@lang('Download') {{ App\Models\GameVersion::latest()->version }}<sup>&dagger;</sup></span>
    </a>
    <div class="mt-1 mb-2 text-xs text-gray-50 drop-shadow shadow-black">
        
        <span>@lang('Released') {{ now()->subYear(1) > App\Models\GameVersion::latest()->release_date ? App\Models\GameVersion::latest()->release_date->isoFormat('D MMMM YYYY') : App\Models\GameVersion::latest()->release_date->diffForHumans() }}</span>
        <span class="px-2">&mdash;</span>
        <span><a href="https://pokemon3d.net/wiki/index.php/Pok%C3%A9mon_3D#Requirements" class="hover:text-gray-300"><sup>&dagger;</sup> @lang('Requirements apply')</a></span>
    </div>
</div>