@extends('layout.main')
@section('title', 'Skins')
     
@section('content')
<div class="row">
    <div class="col-12">
        <h2>Player Skins</h2>
    </div>
</div>
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    @foreach($playerskins as $playerskin)
        <div class="col">
            <div class="card">
                <div class="row g-0">
                    <div class="col-4 p-2">
                        <img src="{{ asset('player/'.$playerskin) }}">
                    </div>
                    <div class="col-8">
                        <div class="card-body">
                            <h5 class="card-title">{{ $playerskin }}</h5>
                            <p class="card-text">
                                <small class="text-muted">{{ \ByteUnits\Binary::bytes(Storage::disk('player')->size($playerskin))->format() }}</small>
                                <br>
                                <small class="text-muted">Owned by {{ App\Models\GJUser::where('gjid', str_replace('.png', '', $playerskin))->first()->gju ?? 'Game Jolt ID: '.str_replace('.png', '', $playerskin) }}</small>
                            </p>
                            <form class="row row-cols-lg-auto g-3 align-items-center" method="post" action="{{ route('skin-destroy-admin', str_replace('.png', '', $playerskin)) }}">
                                <div class="col-12">
                                    <input class="form-control form-control-sm" type="text" name="reason" placeholder="Add a legit reason here">
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-sm btn-outline-danger" href="{{ route('skin-destroy-admin', str_replace('.png', '', $playerskin)) }}">Delete</button>
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