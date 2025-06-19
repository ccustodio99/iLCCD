@extends('layouts.app')

@section('title', 'Ticket Approvals')

@section('content')
<div class="container">
    <h1 class="mb-4">Tickets for Approval</h1>
    @include('components.per-page-selector')
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">Tickets for Approval</caption>
        <thead>
            <tr>
                <th>Category</th>
                <th>Subject</th>
                <th>Requester</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $ticket)
            <tr>
                <td>{{ optional($ticket->ticketCategory)->name ?? 'N/A' }}</td>
                <td>{{ $ticket->formatted_subject }}</td>
                <td>{{ $ticket->user->name }}</td>
                <td>
                    <form action="{{ route('tickets.approve', $ticket) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm btn-primary">Approve</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">No tickets</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    {{ $tickets->links() }}
</div>
@endsection
