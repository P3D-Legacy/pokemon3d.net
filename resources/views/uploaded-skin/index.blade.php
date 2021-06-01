@extends('layout.main')
@section('title', 'Uploaded Skins')
     
@section('content')
<div class="row">
    <div class="col-12">
        <h2>Uploaded Skins</h2>
    </div>
</div>
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    @foreach($skins as $skin)
        <div class="col">
            <div class="card">
                <div class="row g-0">
                    <div class="col-4 p-2">
                        <img src="{{ Storage::disk('skin')->exists($skin->path()) ? asset('skin/'.$skin->path()) : asset('img/noskin.png') }}" height="128" width="96">
                    </div>
                    <div class="col-8">
                        <div class="card-body">
                            <h5 class="card-title">{{ $skin->name }}</h5>
                            <p class="card-text text-muted">
                                <small>{{ $skin->uuid }}</small>
                                <br>
                                <small>{{ Storage::disk('skin')->exists($skin->path()) ? \ByteUnits\Binary::bytes(Storage::disk('skin')->size($skin->path()))->format() : 'N/A' }}</small>
                                <br>
                                <small>Owned by <a href="{{ route('user-show', $skin->owner_id) }}">{{ $skin->user->gju ?? 'Game Jolt ID: '.$skin->owner_id }}</a></small>
                            </p>
                            <form class="row row-cols-lg-auto g-3 align-items-center" method="post" action="{{ route('uploaded-skin-destroy', $skin->uuid) }}">
                                <div class="col-12">
                                    <input class="form-control form-control-sm" type="text" name="reason" placeholder="Add a legit reason here">
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </div>
                                @csrf
                            </form>
                            <p class="m-0 text-muted"><small>Users will be able to see the reason!</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

@endsection