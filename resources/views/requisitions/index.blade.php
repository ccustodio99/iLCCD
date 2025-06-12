@extends('layouts.app')

@section('title', 'Requisitions')

@section('content')
<div class="container">
    <h1 class="mb-4">My Requisitions</h1>
    @include('components.per-page-selector')
    <a href="{{ route('requisitions.create') }}" class="btn btn-primary mb-3">New Requisition</a>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">My Requisitions</caption>
        <thead>
            <tr>
                <th>Items</th>
                <th>Status</th>
                <th>Ticket</th>
                <th>Remarks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($requisitions as $requisition)
            <tr>
                <td>
                    <ul class="mb-0">
                        @foreach($requisition->items as $item)
                        <li>{{ $item->item }} ({{ $item->quantity }})</li>
                        @endforeach
                    </ul>
                </td>
                <td>{{ ucfirst(str_replace('_', ' ', $requisition->status)) }}</td>
                <td>
                    @if($requisition->ticket_id)
                        <a href="{{ route('tickets.index') }}#ticketModal{{ $requisition->ticket_id }}">#{{ $requisition->ticket_id }}</a>
                    @else
                        -
                    @endif
                </td>
                <td>{{ Str::limit($requisition->remarks, 50) }}</td>
                <td>
                    <a href="{{ route('requisitions.edit', $requisition) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('requisitions.destroy', $requisition) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this requisition?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    {{ $requisitions->links() }}
</div>
@endsection
