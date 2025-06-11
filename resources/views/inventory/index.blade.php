@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">My Inventory Items</h1>
    <a href="{{ route('inventory.create') }}" class="btn btn-primary mb-3">Add Item</a>
    <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ ucfirst($item->status) }}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#inventoryItemModal{{ $item->id }}">Details</button>
                    <a href="{{ route('inventory.edit', $item) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('inventory.destroy', $item) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this item?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    {{ $items->links() }}

    @foreach ($items as $item)
    <div class="modal fade" id="inventoryItemModal{{ $item->id }}" tabindex="-1" aria-labelledby="inventoryItemModalLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inventoryItemModalLabel{{ $item->id }}">Item Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Name:</strong> {{ $item->name }}</p>
                    <p><strong>Description:</strong> {{ $item->description }}</p>
                    <p><strong>Category:</strong> {{ $item->category }}</p>
                    <p><strong>Department:</strong> {{ $item->department }}</p>
                    <p><strong>Location:</strong> {{ $item->location }}</p>
                    <p><strong>Supplier:</strong> {{ $item->supplier }}</p>
                    <p><strong>Purchase Date:</strong> {{ optional($item->purchase_date)->format('Y-m-d') }}</p>
                    <p><strong>Quantity:</strong> {{ $item->quantity }}</p>
                    <p><strong>Minimum Stock:</strong> {{ $item->minimum_stock }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($item->status) }}</p>
                    @include('audit_trails._list', ['logs' => $item->auditTrails])
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
