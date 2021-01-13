@extends('layout.main')
@section('title', 'Home')
	 
@section('content')
    <p>id: {{ $id }}</p>
    <p>type: {{ $type }}</p>
    <p>signed_up: {{ $signed_up }}</p>
    <p>last_logged_in: {{ $last_logged_in }}</p>
    <p>status: {{ $status }}</p>
    <p><img src="{{ $avatar_url }}"></p>
@endsection