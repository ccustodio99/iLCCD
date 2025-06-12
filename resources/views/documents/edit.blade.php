@extends('layouts.app')

@section('title', 'Edit Document')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Document</h1>
    <form action="{{ route('documents.update', $document) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $document->title) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $document->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="document_category_id" class="form-select" required>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('document_category_id', $document->document_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Replace File</label>
            <input type="file" name="file" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary me-2">Update</button>
        <a href="{{ route('documents.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
    @include('audit_trails._list', ['logs' => $document->auditTrails])
</div>
@endsection
