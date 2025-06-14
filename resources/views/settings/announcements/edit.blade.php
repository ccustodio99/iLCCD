@extends('layouts.app')

@section('title', 'Edit Announcement')

@section('content')
<div class="container">
    @include('components.breadcrumbs', ['links' => [
        ['label' => 'Settings', 'url' => route('settings.index')],
        ['label' => 'Announcements', 'url' => route('announcements.index')],
        ['label' => 'Edit']
    ]])
    <h1 class="mb-4">Edit Announcement</h1>
    <form action="{{ route('announcements.update', $announcement) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $announcement->title) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" class="form-control" rows="4" required>{{ old('content', $announcement->content) }}</textarea>
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}>
            <label for="is_active" class="form-check-label">Active</label>
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="{{ route('announcements.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
