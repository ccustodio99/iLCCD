@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">My Job Orders</h1>
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#newJobOrderModal">New Job Order</button>
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
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editJobOrderModal{{ $jobOrder->id }}">Edit</button>
                    <form action="{{ route('job-orders.complete', $jobOrder) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Mark this job order as complete?')">Job Complete</button>
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
    <div class="modal fade" id="editJobOrderModal{{ $jobOrder->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Job Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('job-orders.update', $jobOrder) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <input type="text" name="job_type" class="form-control" value="{{ old('job_type', $jobOrder->job_type) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required>{{ old('description', $jobOrder->description) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                @php($statuses = ['new' => 'New', 'approved' => 'Approved', 'assigned' => 'Assigned', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'closed' => 'Closed'])
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $jobOrder->status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <div class="modal fade" id="newJobOrderModal" tabindex="-1" aria-labelledby="newJobOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newJobOrderModalLabel">New Job Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('job-orders.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <input type="text" name="job_type" class="form-control" value="{{ old('job_type') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
