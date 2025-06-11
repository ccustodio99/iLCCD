@extends('layouts.app')

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
    <div class="table-responsive">
    <table class="table table-striped">
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
</div>
@endsection
