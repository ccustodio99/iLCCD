@extends('layouts.app')

@section('title', 'Appearance Settings')

@section('content')
<div class="container">
    @include('components.breadcrumbs', ['links' => [
        ['label' => 'Settings', 'url' => route('settings.index')],
        ['label' => 'Appearance']
    ]])
    <h1 class="mb-4">Appearance Settings</h1>

    <h2 class="h4">Theme Options</h2>
    <form action="{{ route('settings.theme.update') }}" method="POST" class="mb-5">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="color_primary" class="form-label">Primary Color</label>
            <div class="d-flex align-items-center gap-2">
                <input type="color" id="color_primary" name="color_primary" value="{{ $primary }}" class="form-control form-control-color" aria-describedby="primary_help" />
                <span id="primary_color_preview" class="border rounded" style="width: 2rem; height: 2rem; background: {{ $primary }};"></span>
            </div>
            <div id="primary_help" class="form-text">Preview of the primary color</div>
        </div>
        <div class="mb-3">
            <label for="color_accent" class="form-label">Accent Color</label>
            <div class="d-flex align-items-center gap-2">
                <input type="color" id="color_accent" name="color_accent" value="{{ $accent }}" class="form-control form-control-color" aria-describedby="accent_help" />
                <span id="accent_color_preview" class="border rounded" style="width: 2rem; height: 2rem; background: {{ $accent }};"></span>
            </div>
            <div id="accent_help" class="form-text">Preview of the accent color</div>
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
        <div id="theme-preview" class="card p-3 mb-4" style="--color-primary: {{ $primary }}; --color-accent: {{ $accent }}; --font-primary: '{{ $font_primary }}'; --font-secondary: '{{ $font_secondary }}';">
            <h5 class="preview-heading mb-2" style="font-family: var(--font-primary); color: var(--color-primary);">{{ $home_heading }}</h5>
            <p class="preview-tagline mb-0" style="font-family: var(--font-secondary); color: var(--color-accent);">{{ $home_tagline }}</p>
        </div>
        <button type="submit" class="btn btn-primary">Save Theme</button>
    </form>

    <h2 class="h4">Brand Images</h2>
    <form action="{{ route('settings.branding.update') }}" method="POST" enctype="multipart/form-data" class="mb-5">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="logo" class="form-label">Logo</label>
            @if($logo)
                <div class="mb-2">
                    <img src="{{ asset($logo) }}" alt="Current Logo" style="max-height: 80px;">
                </div>
            @endif
            <input type="file" id="logo" name="logo" class="form-control">
        </div>
        <div class="mb-3">
            <label for="favicon" class="form-label">Favicon</label>
            @if($favicon)
                <div class="mb-2">
                    <img src="{{ asset($favicon) }}" alt="Current Favicon" style="max-height: 32px;">
                </div>
            @endif
            <input type="file" id="favicon" name="favicon" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Save Branding</button>
    </form>

    <h2 class="h4">Institution Text</h2>
    <form action="{{ route('settings.institution.update') }}" method="POST" class="mb-5">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="header_text" class="form-label">Header</label>
            <textarea id="header_text" name="header_text" rows="2" class="form-control">{{ $header_text }}</textarea>
        </div>
        <div class="mb-3">
            <label for="footer_text" class="form-label">Footer</label>
            <textarea id="footer_text" name="footer_text" rows="2" class="form-control">{{ $footer_text }}</textarea>
        </div>
        <div class="form-check form-switch mb-3">
            <input type="checkbox" id="show_footer" name="show_footer" value="1" class="form-check-input" {{ $show_footer ? 'checked' : '' }}>
            <label for="show_footer" class="form-check-label">Show Footer</label>
        </div>
        <button type="submit" class="btn btn-primary">Save Institution</button>
    </form>

    <a href="{{ route('settings.index') }}" class="btn btn-secondary">Back to Settings</a>
</div>
@endsection

@push('scripts')
@vite('resources/js/theme-preview.js')
@endpush
