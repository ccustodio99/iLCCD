@extends('layouts.app')

@section('title', 'Manage Licenses')

@section('content')
<div class="container">
    @include('components.breadcrumbs', ['links' => [
        ['label' => 'Licenses']
    ]])
    <h1 class="mb-4">Licenses</h1>
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" aria-live="assertive">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="table-responsive">
    <table class="table table-striped">
        <caption class="visually-hidden">License Records</caption>
        <thead>
            <tr>
                <th>Key</th>
                <th>Expires</th>
                <th>Status</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @foreach($licenses as $license)
            <tr>
                <td>{{ $license->key }}</td>
                <td>{{ $license->expires_at->toFormattedDateString() }}</td>
                <td>
                    @if($license->isValid())
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </td>
                <td>{{ $license->created_at->toFormattedDateString() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
@endsection
