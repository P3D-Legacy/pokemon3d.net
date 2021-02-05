@extends('layout.main')
@section('title', 'Skins')
     
@section('content')
<div class="row">
    <div class="col-12">
        <h2>Skins <a class="btn btn-sm btn-primary" href="{{ route('skin-create') }}">Create</a></h2>
        <p>Slots: {{ \App\Models\GJUser::find(session()->get('gjid'))->skins()->count() }} / {{ env('SKIN_MAX_UPLOAD') }}</p>
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
                                    Public: {{ ($skin->public) ? 'Yes' : 'No' }}<br>
                                    <small class="text-muted">Uploaded: {{ \Carbon\Carbon::parse($skin->created_at)->diffForHumans() }}</small><br>
                                    <small class="text-muted">{{ \ByteUnits\Binary::bytes(Storage::disk('skin')->size($skin->path()))->format() }}</small>
                                    @if($skin->public)<br><small class="text-muted"><i class="far fa-heart"></i> {{ $skin->likers()->count() }} likes</small>@endif
                                </p>
                                <p>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('skin-apply', $skin->uuid) }}">Apply</a>
                                    <a class="btn btn-sm btn-outline-warning" href="{{ route('skin-edit', $skin->uuid) }}">Edit</a>
                                    <a class="btn btn-sm btn-outline-danger" href="{{ route('skin-destroy', $skin->uuid) }}">Delete</a>
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