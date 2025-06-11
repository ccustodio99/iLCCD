@extends('layouts.app')

@section('title', 'Tickets')

@section('content')
<div class="container">
    <h1 class="mb-4">My Tickets</h1>
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#newTicketModal">New Ticket</button>
    <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Category</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Due</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
            <tr>
                <td>{{ $ticket->category }}</td>
                <td>{{ $ticket->subject }}</td>
                <td>{{ ucfirst($ticket->status) }}</td>
                <td>{{ optional($ticket->due_at)->format('Y-m-d') }}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#ticketModal{{ $ticket->id }}">Details</button>
                    <button type="button" class="btn btn-sm btn-primary ms-1" data-bs-toggle="modal" data-bs-target="#editTicketModal{{ $ticket->id }}">Edit</button>
                    <form action="{{ route('tickets.convert', $ticket) }}" method="POST" class="d-inline ms-1">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-secondary" onclick="return confirm('Convert to Job Order?')">Convert</button>
                    </form>
                    <form action="{{ route('tickets.requisition', $ticket) }}" method="POST" class="d-inline ms-1">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Convert to Requisition?')">Requisition</button>
                    </form>
                    <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="d-inline ms-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Archive this ticket?')">Archive</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>

    {{ $tickets->links() }}

    <div class="modal fade" id="newTicketModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tickets.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control" value="{{ old('category') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_at" class="form-control" value="{{ old('due_at') }}">
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

    @foreach ($tickets as $ticket)
    <div class="modal fade" id="ticketModal{{ $ticket->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ticket Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Category:</strong> {{ $ticket->category }}</p>
                    <p><strong>Subject:</strong> {{ $ticket->subject }}</p>
                    <p><strong>Description:</strong> {{ $ticket->description }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($ticket->status) }}</p>
                    <p><strong>Created:</strong> {{ $ticket->created_at->format('Y-m-d H:i') }}</p>
                    <p><strong>Updated:</strong> {{ $ticket->updated_at->format('Y-m-d H:i') }}</p>
                    <p><strong>Escalated:</strong> {{ optional($ticket->escalated_at)->format('Y-m-d H:i') }}</p>
                    <p><strong>Resolved:</strong> {{ optional($ticket->resolved_at)->format('Y-m-d H:i') }}</p>
                    <p><strong>Due:</strong> {{ optional($ticket->due_at)->format('Y-m-d H:i') }}</p>
                    @include('audit_trails._list', ['logs' => $ticket->auditTrails])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editTicketModal{{ $ticket->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tickets.update', $ticket) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control" value="{{ old('category', $ticket->category) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" value="{{ old('subject', $ticket->subject) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required>{{ old('description', $ticket->description) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                @php($statuses = ['open' => 'Open', 'escalated' => 'Escalated', 'converted' => 'Converted', 'closed' => 'Closed'])
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $ticket->status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_at" class="form-control" value="{{ old('due_at', optional($ticket->due_at)->format('Y-m-d')) }}">
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
</div>
@endsection
