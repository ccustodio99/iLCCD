@extends('layouts.app')

@section('title', 'Announcements')

@section('content')
<div class="container">
    @include('components.breadcrumbs', ['links' => [
        ['label' => 'Settings', 'url' => route('settings.index')],
        ['label' => 'Announcements']
    ]])
    <h1 class="mb-4">Announcements</h1>
    @include('components.per-page-selector')
    <a href="{{ route('announcements.create') }}" class="btn btn-sm btn-primary mb-3">Add Announcement</a>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">Announcements</caption>
        <thead>
            <tr>
                <th>Title</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($announcements as $announcement)
            <tr>
                <td>{{ $announcement->title }}</td>
                <td>
                    @if($announcement->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this announcement?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    {{ $announcements->links() }}
</div>
@endsection
