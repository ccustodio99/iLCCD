@extends('layouts.app')

@section('title', 'View Document')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ $document->title }}</h1>
    <p><strong>Category:</strong> {{ $document->category }}</p>
    <p><strong>Description:</strong> {{ $document->description }}</p>
    <p><strong>Department:</strong> {{ $document->department }}</p>

    <h3 class="mt-4">Versions</h3>
    <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Version</th>
                <th>Uploaded By</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($document->versions as $version)
            <tr>
                <td>{{ $version->version }}</td>
                <td>{{ $version->uploader->name }}</td>
                <td>{{ $version->created_at->format('Y-m-d') }}</td>
                <td>
                    <a href="{{ route('documents.download', [$document, $version]) }}" class="btn btn-sm btn-primary">Download</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    <a href="{{ route('documents.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
