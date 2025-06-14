@extends('layouts.app')

@section('title', 'Document Dashboard')

@section('content')
<div class="container">
    <h1 class="mb-4">Document KPI & Log Dashboard</h1>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Uploads</h5>
                    <p class="display-6">{{ $totalUploads }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Versions</h5>
                    <p class="display-6">{{ $totalVersions }}</p>
                </div>
            </div>
        </div>
    </div>
    <h3>Recent Activity</h3>
    @include('components.per-page-selector')
    <div class="mb-3">
        <form method="GET" class="row row-cols-lg-auto g-2 align-items-end">
            <div class="col">
                <label for="filter-user" class="form-label">User</label>
                <select id="filter-user" name="user_id" class="form-select">
                    <option value="">All</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" @selected((string) request('user_id') === (string) $u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="filter-department" class="form-label">Department</label>
                <input id="filter-department" type="text" name="department" value="{{ request('department') }}" class="form-control">
            </div>
            <div class="col">
                <label for="filter-category" class="form-label">Category</label>
                <select id="filter-category" name="document_category_id" class="form-select">
                    <option value="">All</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) request('document_category_id') === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <button type="submit" class="btn btn-secondary">Filter</button>
            </div>
        </form>
    </div>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">Recent Document Activity</caption>
        <thead>
            <tr>
                <th>Document</th>
                <th>User</th>
                <th>Action</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentLogs as $log)
            <tr>
                <td>{{ $log->document->title }}</td>
                <td>{{ $log->user->name }}</td>
                <td>{{ ucfirst($log->action) }}</td>
                <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    {{ $recentLogs->links() }}
</div>
@endsection
