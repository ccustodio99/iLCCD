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
                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#jobOrderModal{{ $jobOrder->id }}">View</button>
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

    @foreach ($jobOrders as $jobOrder)
    <div class="modal fade" id="jobOrderModal{{ $jobOrder->id }}" tabindex="-1" aria-labelledby="jobOrderModalLabel{{ $jobOrder->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jobOrderModalLabel{{ $jobOrder->id }}">Job Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Type:</strong> {{ $jobOrder->job_type }}</p>
                    <p><strong>Description:</strong> {{ $jobOrder->description }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($jobOrder->status) }}</p>
                    @if($jobOrder->ticket_id)
                        <p><strong>Ticket ID:</strong> {{ $jobOrder->ticket_id }}</p>
                    @endif
                    <p><strong>Approved At:</strong> {{ $jobOrder->approved_at?->format('Y-m-d H:i') ?? '-' }}</p>
                    <p><strong>Started At:</strong> {{ $jobOrder->started_at?->format('Y-m-d H:i') ?? '-' }}</p>
                    <p><strong>Completed At:</strong> {{ $jobOrder->completed_at?->format('Y-m-d H:i') ?? '-' }}</p>
                    @if($jobOrder->requisitions->count())
                        <h6>Requisitions</h6>
                        <ul>
                            @foreach($jobOrder->requisitions as $req)
                                <li>{{ $req->item }} - {{ $req->quantity }} ({{ ucfirst($req->status) }})</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
