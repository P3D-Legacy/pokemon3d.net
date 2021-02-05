@extends('layout.main')
@section('title', 'Public Skins')
     
@section('content')
<div class="row">
    <div class="col-12">
        <h2>Public Skins</h2>
    </div>
</div>
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    @foreach($skins as $skin)
        <div class="col">
            <div class="card">
                <div class="row g-0">
                    <div class="col-4 p-2">
                        <img src="{{ asset('skin/'.$skin->path()) }}" height="128" width="96">
                    </div>
                    <div class="col-8">
                        <div class="card-body">
                            <h5 class="card-title">{{ $skin->name }}</h5>
                            <p class="card-text">
                                <p>
                                    <small class="text-muted">Owned by: {{ $skin->user->gju ?? $skin->owner_id }}</small><br>
                                    <small class="text-muted">Uploaded: {{ \Carbon\Carbon::parse($skin->created_at)->diffForHumans() }}</small><br>
                                    <small class="text-muted">File size: {{ \ByteUnits\Binary::bytes(Storage::disk('skin')->size($skin->path()))->format() }}</small><br>
                                    <small class="text-muted"><i class="far fa-heart"></i> {{ $skin->likers()->count() }} likes</small>
                                </p>
                                <p>
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
    @endforeach
</div>

@endsection