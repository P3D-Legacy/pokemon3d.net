@extends('layout.main')
@section('title', $user->username)
     
@section('content')
<div class="row">
    <div class="col-12">
        <h2>{{ $user->gju }} <small class="text-muted">{{ $user->gjid }}</small></h2>
    </div>
</div>
<hr>
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    @if(!$user->skins->count())
        <p>None found.</p>
    @endif
    @foreach($user->skins as $skin)
        @include('skin.component.card', ['skin' => $skin])
    @endforeach
</div>

@endsection