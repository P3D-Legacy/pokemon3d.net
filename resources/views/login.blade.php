@extends('layout.auth')
@section('title', 'Login')
	 
@section('content')
    <form method="post" action="{{ route('login-post') }}">
        <img class="mb-5" src="{{ asset('img/TreeLogoSmall.png') }}" alt="">
        <h1 class="h4 mb-3 fw-normal">Login with <img src="{{ asset('img/gamejolt-logo-dark-1x.png') }}" alt=""></h1>
        <label for="username" class="visually-hidden">Username</label>
        <input type="text" id="username" name="username" class="form-control" placeholder="Username" required="" autofocus="">
        <label for="token" class="visually-hidden">Token</label>
        <input type="password" id="token" name="token" class="form-control" placeholder="Token" required="">
        <button class="w-100 btn btn-success" type="submit"><i class="fas fa-sign-in-alt"></i> Login</button>
        @csrf
    </form>
@endsection