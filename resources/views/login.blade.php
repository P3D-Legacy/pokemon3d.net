@extends('layout.auth')
@section('title', 'Login')
	 
@section('content')
    <form method="post" action="{{ route('login-post') }}" class="form-signin">
        <img class="my-4" src="{{ asset('img/TreeLogoSmall.png') }}" alt="">
        <h1 class="h4 mb-4 fw-normal">Login with <img src="{{ asset('img/gamejolt-logo-light-1x.png') }}" alt=""></h1>
        <label for="username" class="visually-hidden">Username</label>
        <input type="text" id="username" name="username" class="form-control" placeholder="Username" required="" autofocus="">
        <label for="token" class="visually-hidden">Game Token</label>
        <input type="password" id="token" name="token" class="form-control" placeholder="Game Token" required="">
        <p class="fs-6"><small><a href="https://gamejolt.com/help/tokens" class="link-secondary text-decoration-none"><i class="far fa-question-circle"></i> What is my game token?</a></small></p>
        <button class="w-100 btn btn-success mt-2" type="submit"><i class="fas fa-sign-in-alt"></i> Login</button>
        @csrf
    </form>
@endsection