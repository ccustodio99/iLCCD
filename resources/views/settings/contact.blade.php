@extends('layouts.app')

@section('title', 'Contact Information')

@section('content')
<div class="container">
    @include('components.breadcrumbs', ['links' => [
        ['label' => 'Settings', 'url' => route('settings.index')],
        ['label' => 'Contact Information']
    ]])
    <h1 class="mb-4">Contact Information</h1>
    <form action="{{ route('settings.contact.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="contact_email" class="form-label">Email</label>
            <input type="email" id="contact_email" name="contact_email" class="form-control" value="{{ setting('contact_email') }}">
        </div>
        <div class="mb-3">
            <label for="contact_phone" class="form-label">Phone</label>
            <input type="text" id="contact_phone" name="contact_phone" class="form-control" value="{{ setting('contact_phone') }}">
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="{{ route('settings.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
