@extends('layouts.app')

@section('title', 'Manage Licenses')

@section('content')
<div class="container">
    @include('components.breadcrumbs', ['links' => [
        ['label' => 'Licenses']
    ]])
    <h1 class="mb-4">Licenses</h1>
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
