@extends('layouts.app')

@section('title', 'KPI & Audit Dashboard')

@section('content')
<div class="container">
    <h1 class="mb-4">System KPI & Audit Dashboard</h1>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Tickets</h5>
                    <p class="display-6">{{ $ticketsCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Job Orders</h5>
                    <p class="display-6">{{ $jobOrdersCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Requisitions</h5>
                    <p class="display-6">{{ $requisitionsCount }}</p>
                </div>
            </div>
        </div>
    </div>
    <h3>Recent Audit Logs</h3>
    <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>User</th>
                <th>Model</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
            <tr>
                <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $log->user?->name ?? 'System' }}</td>
                <td>{{ class_basename($log->auditable_type) }}#{{ $log->auditable_id }}</td>
                <td>{{ $log->action }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    {{ $logs->links() }}
</div>
@endsection
