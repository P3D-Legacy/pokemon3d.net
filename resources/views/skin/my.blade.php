@extends('layout.main')
@section('title', 'My Skins')
     
@section('content')
<h2 class="text-3xl font-extrabold leading-9 border-b-2 border-gray-100 text-gray-50 mb-4 mt-4 pb-1">
    My Skins &mdash; {{ \App\Models\GJUser::find(session()->get('gjid'))->skins()->count() }} / {{ env('SKIN_MAX_UPLOAD') }}
</h2>
<div class="gap-4 grid grid-flow-row auto-cols-auto md:grid-flow-col md:auto-cols-max">
    @foreach($skins as $skin)
        @include('skin.component.card', ['skin' => $skin])
    @endforeach
</div>

@endsection