@extends('layouts.guest')

@section('title', 'Welcome')

@section('content')
<div class="container my-5">
    <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-start mb-4 mb-md-0">
            <img src="{{ asset('assets/images/CCS.jpg') }}" alt="CCS Logo" class="img-fluid mb-3" style="max-width:200px;">
            <h1 class="mb-3">{{ setting('home_heading', 'Welcome to the LCCD Integrated Information System (CMS)') }}</h1>
            <p class="lead mb-4">{{ setting('home_tagline', 'Empowering Christ-centered digital transformation for La Consolacion College Daet—where technology, transparency, and service unite.') }}</p>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
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
