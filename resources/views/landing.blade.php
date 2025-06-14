@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<style>
    header[role="banner"], nav.sidebar, #toggle-footer, footer {
        display: none !important;
    }
</style>
<div class="modal d-block" tabindex="-1" role="dialog" aria-modal="true" style="background: rgba(0,0,0,0.5); min-height:100vh;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="row g-0">
                <div class="col-md-6 d-flex flex-column align-items-center justify-content-center text-center p-4" style="background-color: var(--color-primary); color: #ffffff;">
                    <img src="{{ asset('assets/images/CCS.jpg') }}" alt="CCS Logo" class="img-fluid mb-4" style="max-width:160px;">
                    <h1 class="h5 mb-3">{{ setting('home_heading', 'Welcome to the LCCD Integrated Information System (CMS)') }}</h1>
                    <p class="mb-0">{{ setting('home_tagline', 'Empowering Christ-centered digital transformation for La Consolacion College Daet—where technology, transparency, and service unite.') }}</p>
                </div>
                <div class="col-md-6 p-4">
                    <h2 class="h5 mb-3">Login <small class="text-muted">to continue to app</small></h2>
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
                            <label class="form-check-label" for="remember">Remember password</label>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <a href="{{ route('password.request') }}" class="me-auto">Forgot Password?</a>
                            <button type="submit" class="btn cta">Login</button>
                        </div>
                        <div class="text-center">
                            Need an account? <a href="{{ route('register') }}">Sign up!</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
