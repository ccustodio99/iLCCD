@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div class="container text-center">
    <img src="{{ asset('assets/images/CCS.jpg') }}" alt="CCS Logo" class="img-fluid mb-4" style="max-width:200px;">
    <h1 class="mb-3">{{ setting('home_heading', 'Welcome to the LCCD Integrated Information System (CMS)') }}</h1>
    <p class="lead mb-4">{{ setting('home_tagline', 'Empowering Christ-centered digital transformation for La Consolacion College Daet—where technology, transparency, and service unite.') }}</p>
    <a href="{{ route('login') }}" class="btn cta btn-lg">Login</a>
</div>
@endsection
