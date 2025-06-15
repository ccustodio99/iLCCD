@extends('layouts.app')

@section('title', 'Institution Settings')

@section('content')
<div class="container">
    @include('components.breadcrumbs', ['links' => [
        ['label' => 'Settings', 'url' => route('settings.index')],
        ['label' => 'Institution']
    ]])
    <h1 class="mb-4">Institution Settings</h1>
    <form action="{{ route('settings.institution.update') }}" method="POST">
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
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="{{ route('settings.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
