<div class="col">
    <div class="card">
        <div class="row g-0">
            <div class="col-4 p-2">
                <img src="{{ Storage::disk('skin')->exists($skin->path()) ? asset('skin/'.$skin->path()) : asset('img/noskin.png') }}" height="128" width="96">
            </div>
            <div class="col-8">
                <div class="card-body">
                    <h5 class="card-title"><a href="{{ route('skin-show', $skin->uuid) }}">{{ $skin->name }}</a></h5>
                    <p class="card-text">
                        <p>
                            <small class="text-muted">Owned by: <a href="{{ route('user-show', $skin->owner_id) }}">{{ $skin->user->gju ?? $skin->owner_id }}</a></small><br>
                            <small class="text-muted">Uploaded: {{ $skin->created_at->diffForHumans() }}</small><br>
                            <small class="text-muted">File size: {{ Storage::disk('skin')->exists($skin->path()) ? \ByteUnits\Binary::bytes(Storage::disk('skin')->size($skin->path()))->format() : 'N/A' }}</small><br>
                            <small class="text-muted"><i class="far fa-heart"></i> {{ $skin->likers()->count() }} likes</small>
                        </p>
                        <p>
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('skin-show', $skin->uuid) }}"><i class="far fa-eye"></i> Show</a>
                            @if(session()->get('gjid') != $skin->owner_id)
                                @if($skin->isLikedBy(\App\Models\GJUser::find(session()->get('gjid'))))
                                    <a class="btn btn-sm btn-danger" href="{{ route('skin-like', $skin->uuid) }}">
                                        <i class="far fa-heart"></i> Liked
                                    </a>
                                @else
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('skin-like', $skin->uuid) }}">
                                        <i class="far fa-heart"></i> Like
                                    </a>
                                @endif
                            @endif
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('skin-apply', $skin->uuid) }}"><i class="far fa-hand-paper"></i> Apply</a>
                        </p>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>