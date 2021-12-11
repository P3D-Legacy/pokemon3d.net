<div class="flex max-w-md overflow-hidden bg-white rounded-lg shadow-lg dark:bg-gray-900">
    <div class="items-center justify-center w-1/4 pt-4 pl-4">
        <img class="mx-auto" src="{{ Storage::disk('skin')->exists($skin->path()) ? asset('img/skin/'.$skin->path()) : asset('img/noskin.png') }}" height="128" width="96">
    </div>
    <div class="w-3/4 p-4">
        <h1 class="text-2xl font-bold text-gray-900 break-all dark:text-gray-100">
            @if($skin->public)
                <a href="{{ route('skin-show', $skin->uuid) }}">{{ $skin->name }}</a>
            @else
                {{ $skin->name }}
            @endif
        </h1>
        <p class="mt-2 text-xs text-gray-600 dark:text-gray-200">
            @if(Auth::user()->gamejolt->id == $skin->owner_id)
                Public: {{ ($skin->public) ? 'Yes' : 'No' }}<br>
            @endif
            @if($skin->user)
                Published by: <a class="text-green-800 hover:text-green-600 dark:text-green-500 dark:hover:text-green-300" href="{{ route('member.show', $skin->user) }}">{{ $skin->user->username }}</a><br>
            @else
                Gamejolt user ID: {{ $skin->owner_id }}<br>
            @endif
            Uploaded: {{ $skin->created_at->diffForHumans() }}<br>
            File size: {{ Storage::disk('skin')->exists($skin->path()) ? \ByteUnits\Binary::bytes(Storage::disk('skin')->size($skin->path()))->format() : 'N/A' }}
        </p>
        <div class="flex mt-2 text-sm text-black item-center dark:text-white">
            <p>
                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                {{ $skin->likers()->count() }} likes
            </p>
        </div>
        <div class="flex justify-between mt-3 item-center">
            @if(request()->is('uploaded/skins*') || request()->is('player/skins'))
                <form class="w-full" method="post" action="{{ route('uploaded-skin-destroy', $skin->uuid) }}">
                    @csrf
                    <p class="m-0 my-2 text-xs text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Users will be able to see the reason for the deletion!
                    </p>
                    <input class="w-full h-8 px-2 text-sm text-gray-700 placeholder-gray-400 border rounded focus:shadow-outline" type="text" name="reason" placeholder="Add a legit reason here">
                    <button class="px-2 py-1 mt-2 text-xs font-bold uppercase bg-red-700 rounded text-red-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Delete
                    </button>
                </form>
            @else
                @if(!request()->is('skins/public/*') && $skin->public)
                    <a class="px-2 py-1 text-xs font-bold uppercase bg-blue-800 rounded text-blue-50" href="{{ route('skin-show', $skin->uuid) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Show
                    </a>
                @endif
                @if(Auth::user()->gamejolt->id != $skin->owner_id)
                    @if($skin->isLikedBy(Auth::user()))
                        <a class="px-2 py-1 text-xs font-bold uppercase bg-red-800 rounded text-red-50" href="{{ route('skin-like', $skin->uuid) }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            Liked
                        </a>
                    @else
                        <a class="px-2 py-1 text-xs font-bold uppercase bg-red-600 rounded text-red-50" href="{{ route('skin-like', $skin->uuid) }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            Like
                        </a>
                    @endif
                @else
                    <a class="px-2 py-1 text-xs font-bold uppercase bg-yellow-600 rounded text-yellow-50" href="{{ route('skin-edit', $skin->uuid) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                    <a class="px-2 py-1 text-xs font-bold uppercase bg-red-700 rounded text-red-50" href="{{ route('skin-destroy', $skin->uuid) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                    </a>
                @endif
                <a class="px-2 py-1 text-xs font-bold uppercase bg-gray-800 rounded text-gray-50" href="{{ route('skin-apply', $skin->uuid) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11" />
                    </svg>
                    Apply
                </a>
            @endif
        </div>
    </div>
</div>