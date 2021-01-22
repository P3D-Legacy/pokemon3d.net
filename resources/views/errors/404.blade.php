@extends('layout.auth')
@section('title', 'Maintenance')
	 
@section('content')
    <p class="fs-1">404</p>
    <p>Seems like you hit a dead end.</p>
    <a href="{{ route('home') }}" class="btn btn-success"><i class="fas fa-home"></i> Home</a>
@endsection