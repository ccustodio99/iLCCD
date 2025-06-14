@extends('layouts.app')

@section('title', 'Branding')

@section('content')
<div class="container">
    <h1 class="mb-4">Branding</h1>
    <form action="{{ route('settings.branding.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="logo" class="form-label">Logo</label>
            @if(setting('logo_path'))
                <div class="mb-2">
                    <img src="{{ asset(setting('logo_path')) }}" alt="Current Logo" style="max-height: 80px;">
                </div>
            @endif
            <input type="file" id="logo" name="logo" class="form-control">
        </div>
        <div class="mb-3">
            <label for="favicon" class="form-label">Favicon</label>
            @if(setting('favicon_path'))
                <div class="mb-2">
                    <img src="{{ asset(setting('favicon_path')) }}" alt="Current Favicon" style="max-height: 32px;">
                </div>
            @endif
            <input type="file" id="favicon" name="favicon" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="{{ route('settings.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
