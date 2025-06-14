@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="container" style="max-width: 400px;">
    <h1 class="mb-4 text-center">Forgot Password</h1>
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn cta">Send Reset Link</button>
        </div>
    </form>
</div>
@endsection
