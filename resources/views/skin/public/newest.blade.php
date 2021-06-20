@extends('layout.main')
@section('title', 'Public Skins')
     
@section('content')
<div class="w-full shadow-lg bg-white dark:bg-gray-700 items-center rounded-2xl z-40 mt-4 p-4">
    <h2 class="text-3xl font-extrabold text-gray-900">Public Skins</h2>
</div>
<div>
    <div class="col-12">
        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" href="{{ route('skins-newest') }}">Newest</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('skins-popular') }}">Most Popular</a>
            </li>
        </ul>
        <div class="flex flex-auto">
            @if(!$skins->count())
                <p>None found.</p>
            @endif
            @foreach($skins as $skin)
                @include('skin.component.card', ['skin' => $skin])
            @endforeach
        </div>
        
    </div>
    <div class="col-12">
        {{ $skins->links() }}
    </div>
</div>

@endsection