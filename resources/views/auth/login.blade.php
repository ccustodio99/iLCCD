@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 400px;">
    <h1 class="mb-4 text-center">Login</h1>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" class="form-control" name="password" required>
        </div>
        <div class="mb-3 form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label" for="remember">Remember Me</label>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn cta">Login</button>
        </div>
        <div class="mt-3">
            <a href="{{ route('password.request') }}">Forgot Your Password?</a>
        </div>
        <div class="mt-2">
            <a href="{{ route('register') }}">Create an Account</a>
        </div>
        <div class="mt-2">
            <a href="{{ route('home') }}">Back to Home</a>
        </div>
    </form>
</div>
@endsection
