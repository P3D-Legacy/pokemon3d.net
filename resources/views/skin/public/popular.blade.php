@extends('layout.main')
@section('title', 'Public Skins')
     
@section('content')
<h2 class="text-3xl font-extrabold leading-9 border-b-2 border-gray-100 text-gray-50 mb-4 mt-4 pb-1">
    Public Skins
</h2>

<div class="flex items-center mb-4">
    <a class="border-l border-t border-b text-base font-medium rounded-l-md hover:bg-gray-100 px-4 py-2 {{ request()->is('skins/public/new*') ? 'text-green-800 bg-green-50' : 'text-gray-800' }} bg-white" href="{{ route('skins-newest') }}">Newest</a>
    <a class="border-t border-b border-r text-base font-medium rounded-r-md hover:bg-gray-100 px-4 py-2 {{ request()->is('skins/public/popular*') ? 'text-green-800 bg-green-50' : 'text-gray-800 bg-white' }}" href="{{ route('skins-popular') }}">Most Popular</a>
</div>

<div class="gap-4 grid grid-flow-row auto-cols-auto md:grid-flow-col md:auto-cols-max">
    @if(!$skins->count())
        <p>None found.</p>
    @endif
    @foreach($skins as $skin)
        @include('skin.component.card', ['skin' => $skin])
    @endforeach
</div>

<div class="mt-4">
    {{ $skins->links() }}
</div>

@endsection