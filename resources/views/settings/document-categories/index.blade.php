@extends('layouts.app')

@section('title', 'Document Categories')

@section('content')
<div class="container">
    @include('components.breadcrumbs', ['links' => [
        ['label' => 'Settings', 'url' => route('settings.index')],
        ['label' => 'Document Categories']
    ]])
    <h1 class="mb-4">Document Categories</h1>
    @include('components.per-page-selector')
    <a href="{{ route('document-categories.create') }}" class="btn btn-sm btn-primary mb-3">Add Category</a>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">Document Categories</caption>
        <thead>
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>
                    @if($category->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('document-categories.edit', $category) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('document-categories.destroy', $category) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    {{ $categories->links() }}
</div>
@endsection
