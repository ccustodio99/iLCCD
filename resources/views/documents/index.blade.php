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
                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#documentModal{{ $document->id }}">Details</button>
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

    @foreach ($documents as $document)
    <div class="modal fade" id="documentModal{{ $document->id }}" tabindex="-1" aria-labelledby="documentModalLabel{{ $document->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="documentModalLabel{{ $document->id }}">Document Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Title:</strong> {{ $document->title }}</p>
                    <p><strong>Description:</strong> {{ $document->description }}</p>
                    <p><strong>Category:</strong> {{ $document->category }}</p>
                    <p><strong>Current Version:</strong> {{ $document->current_version }}</p>
                    @include('audit_trails._list', ['logs' => $document->auditTrails])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
