<div class="w-full p-6 pt-4 mt-6 mb-10 overflow-hidden bg-white rounded-lg shadow-md sm:max-w-2xl dark:bg-gray-800">

    <div class="mb-6">
        <h3 class="text-lg font-semibold dark:text-white mb-1">@lang('Comments') ({{ $post->commentCount() }})</h3>
        <button onclick="Livewire.emit('openModal', 'comment-modal', {{ json_encode(['post' => $post->id]) }})" class="px-2 py-1 text-sm font-bold text-white bg-gray-500 rounded hover:bg-gray-700"><svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg> @lang('Comment')</button>
    </div>

    <div class="space-y-4">
        @forelse($comments as $comment)
            <div class="flex">
                <div class="flex-shrink-0 mr-3">
                    <img class="mt-2 rounded-full w-8 h-8 sm:w-10 sm:h-10" src="{{ $comment->creator->profile_photo_url }}" alt="">
                </div>
                <div class="flex-1 border rounded-lg px-4 py-2 sm:px-6 sm:py-4 leading-relaxed dark:border-gray-600 dark:text-white">
                    <strong>{{ $comment->creator->username }}</strong> <span class="text-xs text-gray-400">{{ $comment->created_at->isoFormat('LLL') }}</span>
                    <p class="text-sm text-clip">
                        {{ $comment->body }}
                    </p>
                    <button onclick="Livewire.emit('openModal', 'comment-modal', {{ json_encode(['post' => $comment->id, 'parentComment' => $comment->id]) }})" class="px-2 py-1 text-sm font-bold text-gray-400 hover:text-green-300"><svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg> @lang('Reply')</button>
                    @if($comment->hasChildren())
                        <h4 class="my-5 uppercase tracking-wide text-gray-400 font-bold text-xs">@lang('Replies')</h4>
                        <div class="space-y-4">
                            @foreach($comment->children as $comment)
                                <div class="flex">
                                    <div class="flex-shrink-0 mr-3">
                                        <img class="mt-2 rounded-full w-8 h-8 sm:w-10 sm:h-10" src="{{ $comment->creator->profile_photo_url }}" alt="">
                                    </div>
                                    <div class="flex-1 border rounded-lg px-4 py-2 sm:px-6 sm:py-4 leading-relaxed dark:border-gray-600 dark:text-white">
                                        <strong>{{ $comment->creator->username }}</strong> <span class="text-xs text-gray-400">{{ $comment->created_at->isoFormat('LLL') }}</span>
                                        <p class="text-sm">
                                            {{ $comment->body }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-600">No comments yet.</p>
        @endforelse
    </div>
</div>
