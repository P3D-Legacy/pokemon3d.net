<div class="inline-block mt-2 md:mt-6">
    <a href="{{ GitHubHelper::getDownloadUrl() }}" class="mx-auto lg:mx-0 font-extrabold rounded-lg py-4 px-8 shadow-2xl w-76 inline-flex items-center justify-center  text-green-50 group-hover:text-green-100 bg-green-500 hover:bg-green-600 border border-green-400 transition hover:-translate-y-1 duration-150 shadow-black/50">
        {{--
            WINDOWS ICON
            
            <svg version="1.2" baseProfile="tiny-ps" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-opacity-50 transform" viewBox="0 0 58 58" width="58" height="58" fill="currentColor" stroke="none"><path class="shp0" d="M0 14L0 28L28 28L28 0L0 0L0 14ZM30 14L30 28L58 28L58 0L30 0L30 14ZM0 44L0 58L28 58L28 30L0 30L0 44ZM30 44L30 58L58 58L58 30L30 30L30 44Z" /></svg>
        --}}
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6 mr-3 text-opacity-50 transform">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
        </svg>
        <span>Download {{ GitHubHelper::getVersion() }}<sup>&dagger;</sup></span>
    </a>
    <div class="text-xs mt-1 mb-2 text-gray-100">
        <span>Released {{ \Carbon\Carbon::parse(GitHubHelper::getReleaseDate())->diffForHumans() }}</span>
        <span class="px-2">&mdash;</span>
        <span><a href="https://pokemon3d.net/wiki/index.php/Pok%C3%A9mon_3D#Requirements" class="hover:text-gray-300"><sup>&dagger;</sup> Requirements apply</a></span>
    </div>
</div>