@extends('layout.main')
@section('title', 'Public Skins')
     
@section('content')
<div class="row">
    <div class="col-12">
        <h2>Public Skins</h2>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('skins-newest') }}">Newest</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link active" href="{{ route('skins-popular') }}">Most Popular</a>
            </li>
        </ul>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @if(!$skins->count())
                <p>None found.</p>
            @endif
            @foreach($skins as $skin)
                @include('skin.component.card', ['skin' => $skin])
            @endforeach
        </div>
        
    </div>
</div>

@endsection