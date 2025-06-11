@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">My Job Orders</h1>
    <a href="{{ route('job-orders.create') }}" class="btn btn-primary mb-3">New Job Order</a>
    <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Type</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jobOrders as $jobOrder)
            <tr>
                <td>{{ $jobOrder->job_type }}</td>
                <td>{{ Str::limit($jobOrder->description, 50) }}</td>
                <td>{{ ucfirst($jobOrder->status) }}</td>
                <td>
                    <a href="{{ route('job-orders.edit', $jobOrder) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('job-orders.destroy', $jobOrder) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this job order?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    {{ $jobOrders->links() }}
</div>
@endsection
