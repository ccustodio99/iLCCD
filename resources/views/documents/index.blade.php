@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">My Documents</h1>
    <a href="{{ route('documents.create') }}" class="btn btn-primary mb-3">Upload Document</a>
    <div class="table-responsive">
    <table class="table table-striped">
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
                <td>{{ ucfirst($document->category) }}</td>
                <td>{{ $document->current_version }}</td>
                <td>
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
