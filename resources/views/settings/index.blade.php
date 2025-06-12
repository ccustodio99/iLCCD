@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="container">
    <h1 class="mb-4">System Settings</h1>
    <ul class="list-group">
        <li class="list-group-item"><a href="{{ route('ticket-categories.index') }}">Ticket Categories</a></li>
        <li class="list-group-item"><a href="{{ route('job-order-types.index') }}">Job Order Types</a></li>
        <li class="list-group-item"><a href="{{ route('inventory-categories.index') }}">Inventory Categories</a></li>
        <li class="list-group-item"><a href="{{ route('document-categories.index') }}">Document Categories</a></li>
        <li class="list-group-item"><a href="{{ route('settings.theme') }}">Theme</a></li>
    </ul>
</div>
@endsection
