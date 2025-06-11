@extends('layouts.app')

@section('title', 'Create Account')

@section('content')
<div class="container" style="max-width: 400px;">
    <h1 class="mb-4 text-center">Create Account</h1>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" class="form-control" name="password" required>
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn cta">Register</button>
        </div>
        <div class="mt-3">
            <a href="{{ route('login') }}">Already have an account? Login</a>
        </div>
        <div class="mt-2">
            <a href="{{ route('home') }}">Back to Home</a>
        </div>
    </form>
</div>
@endsection
