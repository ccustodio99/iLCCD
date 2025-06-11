@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Audit Trail</h1>
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
