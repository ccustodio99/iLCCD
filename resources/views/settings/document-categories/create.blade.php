@extends('layouts.app')

@section('title', 'Add Document Category')

@section('content')
<div class="container">
    <h1 class="mb-4">Add Document Category</h1>
    <form action="{{ route('document-categories.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
            <label for="is_active" class="form-check-label">Active</label>
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="{{ route('document-categories.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
