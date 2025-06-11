@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">My Tickets</h1>
    <a href="{{ route('tickets.create') }}" class="btn btn-primary mb-3">New Ticket</a>
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
                    <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-sm btn-primary ms-1">Edit</a>
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
