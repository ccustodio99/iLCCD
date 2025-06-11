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
                    <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('tickets.convert', $ticket) }}" method="POST" class="d-inline ms-1">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-secondary" onclick="return confirm('Convert to Job Order?')">Convert</button>
                    </form>
                    <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="d-inline ms-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this ticket?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    {{ $tickets->links() }}
</div>
@endsection
