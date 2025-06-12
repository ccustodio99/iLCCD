@extends('layouts.app')

@section('title', 'Theme Settings')

@section('content')
<div class="container">
    <h1 class="mb-4">Theme Settings</h1>
    <form action="{{ route('settings.theme.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="color_primary" class="form-label">Primary Color</label>
            <input type="color" id="color_primary" name="color_primary" value="{{ $primary }}" class="form-control form-control-color" />
        </div>
        <div class="mb-3">
            <label for="color_accent" class="form-label">Accent Color</label>
            <input type="color" id="color_accent" name="color_accent" value="{{ $accent }}" class="form-control form-control-color" />
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
