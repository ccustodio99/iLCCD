@extends('layouts.app')

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
            <input type="text" name="category" class="form-control" value="{{ old('category', $document->category) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Replace File</label>
            <input type="file" name="file" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
    @include('audit_trails._list', ['logs' => $document->auditTrails])
</div>
@endsection
