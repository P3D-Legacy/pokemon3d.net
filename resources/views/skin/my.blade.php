@extends('layout.main')
@section('title', 'My Skins')
     
@section('content')
<div class="row">
    <div class="col-12">
        <h2>My Skins <a class="btn btn-sm btn-primary" href="{{ route('skin-create') }}">Create</a></h2>
        <p>Slots: {{ \App\Models\GJUser::find(session()->get('gjid'))->skins()->count() }} / {{ env('SKIN_MAX_UPLOAD') }}</p>
    </div>
</div>
<div class="gap-4 grid grid-flow-row auto-cols-auto md:grid-flow-col md:auto-cols-max">
    @foreach($skins as $skin)
        @include('skin.component.card', ['skin' => $skin])
    @endforeach
</div>

@endsection