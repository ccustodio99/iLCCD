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
        <div class="mb-3">
            <label for="font_primary" class="form-label">Primary Font</label>
            <select id="font_primary" name="font_primary" class="form-select">
                @foreach(['Poppins', 'Roboto', 'Montserrat'] as $font)
                    <option value="{{ $font }}" {{ $font_primary === $font ? 'selected' : '' }}>{{ $font }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="font_secondary" class="form-label">Secondary Font</label>
            <select id="font_secondary" name="font_secondary" class="form-select">
                @foreach(['Poppins', 'Roboto', 'Montserrat'] as $font)
                    <option value="{{ $font }}" {{ $font_secondary === $font ? 'selected' : '' }}>{{ $font }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="home_heading" class="form-label">Home Page Heading</label>
            <input type="text" id="home_heading" name="home_heading" value="{{ $home_heading }}" class="form-control" />
        </div>
        <div class="mb-3">
            <label for="home_tagline" class="form-label">Home Page Tagline</label>
            <textarea id="home_tagline" name="home_tagline" rows="3" class="form-control">{{ $home_tagline }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
