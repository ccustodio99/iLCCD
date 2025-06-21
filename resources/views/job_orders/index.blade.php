@extends('layouts.app')

@section('title', 'Job Orders')

@section('content')
<div class="container">
    <h1 class="mb-4">Job Orders</h1>
    @include('components.per-page-selector')
    <div class="mb-3">
        <form method="GET" class="row row-cols-lg-auto g-2 align-items-end">
            <div class="col">
                <label for="filter-status" class="form-label">Status</label>
                <select id="filter-status" name="status" class="form-select">
                    <option value="">Any</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="filter-type" class="form-label">Type</label>
                <select id="filter-type" name="type_parent" class="form-select">
                    <option value="">Any</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" @selected((string)request('type_parent') === (string)$type->id)>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="filter-assigned" class="form-label">Assigned To</label>
                <select id="filter-assigned" name="assigned_to_id" class="form-select">
                    <option value="">Any</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" @selected((string)request('assigned_to_id') === (string)$u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="filter-search" class="form-label">Search</label>
                <input id="filter-search" type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Description">
            </div>
            <div class="col form-check mt-4">
                <input class="form-check-input" type="checkbox" value="1" id="filter-closed" name="closed" {{ request('closed') ? 'checked' : '' }}>
                <label class="form-check-label" for="filter-closed">Include Closed</label>
            </div>
            <div class="col">
                <button type="submit" class="btn btn-secondary">Filter</button>
            </div>
        </form>
    </div>
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#newJobOrderModal">New Job Order</button>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">Job Orders</caption>
        <thead>
            <tr>
                <th>Type</th>
                <th>Description</th>
                <th>Status</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jobOrders as $jobOrder)
            <tr>
                <td>{{ $jobOrder->job_type }}</td>
                <td>{{ Str::limit($jobOrder->description, 50) }}</td>
                <td>{{ ucfirst($jobOrder->status) }}</td>
                <td>{{ $jobOrder->user_id === auth()->id() ? 'Requester' : 'Assignee' }}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#jobOrderModal{{ $jobOrder->id }}">View</button>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editJobOrderModal{{ $jobOrder->id }}">Edit</button>
                    @if($jobOrder->status === \App\Models\JobOrder::STATUS_COMPLETED)
                        <form action="{{ route('job-orders.close', $jobOrder) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Close this job order?')">Close</button>
                        </form>
                    @elseif($jobOrder->user_id === auth()->id())
                        <form action="{{ route('job-orders.complete', $jobOrder) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Mark this job order as complete?')">Job Complete</button>
                        </form>
                    @endif
                    @if(auth()->user()->role === 'head' && (
                            ($jobOrder->status === \App\Models\JobOrder::STATUS_PENDING_HEAD && auth()->user()->department === $jobOrder->user->department) ||
                            ($jobOrder->status === \App\Models\JobOrder::STATUS_PENDING_PRESIDENT && auth()->user()->department === 'President Department') ||
                            ($jobOrder->status === \App\Models\JobOrder::STATUS_PENDING_FINANCE && auth()->user()->department === 'Finance Office')
                        ))
                        <form action="{{ route('job-orders.approve', $jobOrder) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')

                            <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Approve this job order?')">Approve</button>

                        </form>
                    @endif
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
                    <p><strong>Role:</strong> {{ $jobOrder->user_id === auth()->id() ? 'Requester' : 'Assignee' }}</p>
                    @if($jobOrder->ticket_id)
                        <p><strong>Ticket ID:</strong>
                            <a href="{{ route('tickets.index') }}#ticketModal{{ $jobOrder->ticket_id }}">
                                {{ $jobOrder->ticket_id }}
                            </a>
                        </p>
                    @endif
                    <p><strong>Approved At:</strong> {{ $jobOrder->approved_at?->format('Y-m-d H:i') ?? '-' }}</p>
                    <p><strong>Started At:</strong> {{ $jobOrder->started_at?->format('Y-m-d H:i') ?? '-' }}</p>
                    <p><strong>Completed At:</strong> {{ $jobOrder->completed_at?->format('Y-m-d H:i') ?? '-' }}</p>
                    @if($jobOrder->attachment_path)
                        <p><strong>Attachment:</strong> <a href="{{ route('job-orders.attachment', $jobOrder) }}" target="_blank">Download</a></p>
                    @endif
                    @if($jobOrder->requisitions->count())
                        <h6>Requisitions</h6>
                        <ul>
                            @foreach($jobOrder->requisitions as $req)
                                <li>
                                    <ul class="mb-0">
                                        @foreach($req->items as $item)
                                            <li>{{ $item->item }} ({{ $item->quantity }})</li>
                                        @endforeach
                                    </ul>
                                    <span class="ms-2">({{ ucfirst($req->status) }})</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    @include('audit_trails._list', ['logs' => $jobOrder->auditTrails])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @php($child = \App\Models\JobOrderType::where('name', $jobOrder->job_type)->first())
    @php($parentIdCurrent = $child?->parent_id)
    <div class="modal fade" id="editJobOrderModal{{ $jobOrder->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Job Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('job-orders.update', $jobOrder) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type_parent" id="type_parent_{{ $jobOrder->id }}" class="form-select" required>
                                <option value="">Select Type</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ old('type_parent', $parentIdCurrent) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sub Type</label>
                            <select name="job_type" id="job_type_{{ $jobOrder->id }}" class="form-select" required disabled>
                                <option value="">Select Sub Type</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required>{{ old('description', $jobOrder->description) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Attachment</label>
                            <input type="file" name="attachment" class="form-control">
                            @if($jobOrder->attachment_path)
                                <small class="text-muted">Current: <a href="{{ route('job-orders.attachment', $jobOrder) }}" target="_blank">Download</a></small>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $jobOrder->status)) }}" disabled>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
                <script>
                (function () {
                    const parent{{ $jobOrder->id }} = document.getElementById('type_parent_{{ $jobOrder->id }}');
                    const child{{ $jobOrder->id }} = document.getElementById('job_type_{{ $jobOrder->id }}');

                    function loadChildren{{ $jobOrder->id }}(id, selected) {
                        child{{ $jobOrder->id }}.innerHTML = '<option value="">Select Sub Type</option>';
                        if (!id) {
                            child{{ $jobOrder->id }}.disabled = true;
                            return;
                        }
                        child{{ $jobOrder->id }}.disabled = false;
                        fetch(`/job-order-types/${id}/children`)
                            .then(r => r.json())
                            .then(data => {
                                data.forEach(c => {
                                    const opt = document.createElement('option');
                                    opt.value = c.name;
                                    opt.textContent = c.name;
                                    if (selected === c.name) opt.selected = true;
                                    child{{ $jobOrder->id }}.appendChild(opt);
                                });
                            });
                    }

                    parent{{ $jobOrder->id }}.addEventListener('change', () => loadChildren{{ $jobOrder->id }}(parent{{ $jobOrder->id }}.value));
                    loadChildren{{ $jobOrder->id }}(parent{{ $jobOrder->id }}.value, '{{ old('job_type', $jobOrder->job_type) }}');
                })();
                </script>
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
                <form action="{{ route('job-orders.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type_parent" id="new_type_parent" class="form-select" required>
                                <option value="">Select Type</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ old('type_parent') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sub Type</label>
                            <select name="job_type" id="new_job_type" class="form-select" required disabled>
                                <option value="">Select Sub Type</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Attachment</label>
                            <input type="file" name="attachment" class="form-control">
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
<script>
(function () {
    const parent = document.getElementById('new_type_parent');
    const child = document.getElementById('new_job_type');

    function loadChildren(id, selected) {
        child.innerHTML = '<option value="">Select Sub Type</option>';
        if (!id) {
            child.disabled = true;
            return;
        }
        child.disabled = false;
        fetch(`/job-order-types/${id}/children`)
            .then(r => r.json())
            .then(data => {
                data.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.name;
                    opt.textContent = c.name;
                    if (selected === c.name) opt.selected = true;
                    child.appendChild(opt);
                });
            });
    }

    parent.addEventListener('change', () => loadChildren(parent.value));
    loadChildren(parent.value, '{{ old('job_type') }}');
})();
</script>
@endsection
