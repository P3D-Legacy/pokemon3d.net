@extends('layout.auth')
@section('title', 'Server Error')
	 
@section('content')
    <p class="fs-1">500</p>
    <p>Oops! Something went wrong.</p>
    <p>We have gotten a notification about this.</p>
    <a href="{{ route('home') }}" class="btn btn-success"><i class="fas fa-home"></i> Home</a>
@endsection