@extends('layouts.app')

@section('title', 'Job Order Types')

@section('content')
<div class="container">
    @include('components.breadcrumbs', ['links' => [
        ['label' => 'Settings', 'url' => route('settings.index')],
        ['label' => 'Job Order Types']
    ]])
    <h1 class="mb-4">Job Order Types</h1>
    @include('components.per-page-selector')
    <a href="{{ route('job-order-types.create') }}" class="btn btn-sm btn-primary mb-3">Add Type</a>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">Job Order Types</caption>
        <thead>
            <tr>
                <th>Name</th>
                <th>Parent</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($types as $type)
            <tr>
                <td>{{ $type->name }}</td>
                <td>{{ optional($type->parent)->name }}</td>
                <td>
                    @if($type->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('job-order-types.edit', $type) }}" class="btn btn-sm btn-primary">Edit</a>
                    @if($type->is_active)
                        <form action="{{ route('job-order-types.disable', $type) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-warning">Disable</button>
                        </form>
                    @endif
                    <form action="{{ route('job-order-types.destroy', $type) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this type?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    {{ $types->links() }}
</div>
@endsection
