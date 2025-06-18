@php($hideHeader = true)
@extends('layouts.guest')

@section('title', 'Welcome')

@push('styles')
<style>
    .hero-left {
        background: linear-gradient(135deg, var(--color-primary), var(--color-accent));
        color: #ffffff;
    }
</style>
@endpush

@section('content')

<div class="container-fluid">
    <div class="row g-0 min-vh-100">
        <div class="col-md-6 d-flex flex-column justify-content-center align-items-center text-center p-5 hero-left">
            <img src="{{ asset('assets/images/LCCD.jpg') }}" alt="LCCD Logo" class="img-fluid mb-4" style="max-width:200px;">
            <h1 class="display-5 fw-bold mb-3">{{ setting('home_heading', 'Welcome to the LCCD Integrated Information System') }}</h1>
            <p class="lead">{{ setting('home_tagline', 'Empowering Christ-centered digital transformation for La Consolacion College Daet—where technology, transparency, and service unite.') }}</p>
        </div>
        <div class="col-md-6 d-flex align-items-center justify-content-center p-5">
            <div class="card shadow-sm w-100" style="max-width: 400px;">
                <div class="card-body">
                    <h2 class="mb-4 text-center">Login</h2>
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
            </div>
        </div>
    </div>
</div>
@endsection
