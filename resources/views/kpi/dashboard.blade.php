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
    @include('components.per-page-selector')
    <div class="mb-3">
        <form method="GET" class="row row-cols-lg-auto g-2 align-items-end">
            <div class="col">
                <label for="filter-date-from" class="form-label">From</label>
                <input id="filter-date-from" type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
            </div>
            <div class="col">
                <label for="filter-date-to" class="form-label">To</label>
                <input id="filter-date-to" type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
            </div>
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
                <label for="filter-module" class="form-label">Module</label>
                <select id="filter-module" name="module" class="form-select">
                    <option value="">All</option>
                    @foreach($modules as $value => $label)
                        <option value="{{ $value }}" @selected(request('module') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="filter-action" class="form-label">Action</label>
                <select id="filter-action" name="action" class="form-select">
                    <option value="">All</option>
                    @foreach($actions as $act)
                        <option value="{{ $act }}" @selected(request('action') === $act)>{{ ucfirst($act) }}</option>
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
    <caption class="visually-hidden">Recent Audit Logs</caption>
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
