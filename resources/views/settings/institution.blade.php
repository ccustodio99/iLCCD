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
            <label for="institution_address" class="form-label">Address</label>
            <textarea id="institution_address" name="institution_address" rows="3" class="form-control">{{ $address }}</textarea>
        </div>
        <div class="mb-3">
            <label for="institution_phone" class="form-label">Phone</label>
            <input type="text" id="institution_phone" name="institution_phone" value="{{ $phone }}" class="form-control" />
        </div>
        <div class="mb-3">
            <label for="helpdesk_email" class="form-label">Helpdesk Email</label>
            <input type="email" id="helpdesk_email" name="helpdesk_email" value="{{ $email }}" class="form-control" />
        </div>
        <div class="mb-3">
            <label for="header_text" class="form-label">Header Text</label>
            <input type="text" id="header_text" name="header_text" value="{{ $header_text }}" class="form-control" />
        </div>
        <div class="mb-3">
            <label for="footer_text" class="form-label">Footer Text</label>
            <input type="text" id="footer_text" name="footer_text" value="{{ $footer_text }}" class="form-control" />
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
