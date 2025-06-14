@extends('layouts.app')

@section('title', 'Create Account')

@section('content')
<div class="container" style="max-width: 400px;">
    <h1 class="mb-4 text-center">Create Account</h1>
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
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
            <label for="contact_info" class="form-label">Contact Information</label>
            <input id="contact_info" type="text" class="form-control" name="contact_info" value="{{ old('contact_info') }}">
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
