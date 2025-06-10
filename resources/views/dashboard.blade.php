@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h1 class="mb-4">Dashboard</h1>
    <p>You are logged in!</p>
    <a href="{{ route('tickets.index') }}" class="btn cta mt-3">Go to Tickets</a>
</div>
@endsection
