@extends('layouts.app')

@section('title', 'Documents')

@section('content')
<div class="container">
    <h1 class="mb-4">My Documents</h1>
    @include('components.per-page-selector')
    <div class="mb-3">
        <form method="GET" class="row row-cols-lg-auto g-2 align-items-end">
            <div class="col">
                <label for="filter-category" class="form-label">Category</label>
                <select id="filter-category" name="category" class="form-select">
                    <option value="">Any</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="filter-from" class="form-label">From</label>
                <input id="filter-from" type="date" name="from" value="{{ request('from') }}" class="form-control">
            </div>
            <div class="col">
                <label for="filter-to" class="form-label">To</label>
                <input id="filter-to" type="date" name="to" value="{{ request('to') }}" class="form-control">
            </div>
            <div class="col">
                <label for="filter-search" class="form-label">Search</label>
                <input id="filter-search" type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Title or description">
            </div>
            <div class="col">
                <button type="submit" class="btn btn-secondary">Filter</button>
            </div>
        </form>
    </div>
    <a href="{{ route('documents.create') }}" class="btn btn-primary mb-3">Upload Document</a>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">My Documents</caption>
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Version</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($documents as $document)
            <tr>
                <td>{{ $document->title }}</td>
                <td>{{ $document->documentCategory->name }}</td>
                <td>{{ $document->current_version }}</td>
                <td>
                    <a href="{{ route('documents.show', $document) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('documents.edit', $document) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('documents.destroy', $document) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this document?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    {{ $documents->links() }}
</div>
@endsection
