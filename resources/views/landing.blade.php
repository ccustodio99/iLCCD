@extends('layouts.app')

@section('content')
<div class="container text-center">
    <img src="{{ asset('assets/images/CCS.jpg') }}" alt="CCS Logo" class="img-fluid mb-4" style="max-width:200px;">
    <h1 class="mb-3">Welcome to the LCCD Integrated Information System</h1>
    <p class="lead mb-4">Empowering Christ-centered digital transformation for La Consolacion College Daet—where technology, transparency, and service unite.</p>
    <a href="{{ route('login') }}" class="btn cta btn-lg">Login</a>
</div>
@endsection
