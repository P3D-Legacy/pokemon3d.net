@extends('layout.main')
@section('title', $user->gju)
     
@section('content')
<h2 class="text-3xl font-extrabold leading-9 border-b-2 border-gray-100 text-gray-50 mb-4 mt-4 pb-1">
    {{ $user->gju }} <small class="text-muted">{{ $user->gjid }}</small>
</h2>

<div class="gap-4 grid grid-cols-1 grid-flow-row auto-rows-max sm:grid-cols-2 lg:grid-cols-3">
    @if(!$user->skins->count())
        <p class="text-white">None found.</p>
    @endif
    @foreach($user->publicSkins as $skin)
        @include('skin.component.card', ['skin' => $skin])
    @endforeach
</div>

@endsection