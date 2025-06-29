@extends('layouts.app')

@section('title', 'Inventory')

@section('content')
<div class="container">
    <h1 class="mb-4">My Inventory Items</h1>
    @include('components.per-page-selector')
    <div class="mb-3">
        <form method="GET" class="row row-cols-lg-auto g-2 align-items-end">
            <div class="col">
                <label for="filter-category" class="form-label">Category</label>
                <select id="filter-category" name="category" class="form-select">
                    <option value="">All</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="filter-status" class="form-label">Status</label>
                <select id="filter-status" name="status" class="form-select">
                    <option value="">Any</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="filter-search" class="form-label">Search</label>
                <input id="filter-search" type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Name">
            </div>
            <div class="col">
                <button type="submit" class="btn btn-secondary">Filter</button>
            </div>
        </form>
    </div>
    <a href="{{ route('inventory.create') }}" class="btn btn-primary mb-3">Add Item</a>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">Inventory Items</caption>
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
            <tr class="{{ $item->quantity == 0 ? 'table-danger' : ($item->quantity <= $item->minimum_stock ? 'table-warning' : '') }}">
                <td>{{ $item->name }}</td>
                <td>
                    @if($item->quantity == 0)
                        <span class="badge bg-danger">0</span>
                    @elseif($item->quantity <= $item->minimum_stock)
                        <span class="badge bg-warning text-dark">{{ $item->quantity }}</span>
                    @else
                        {{ $item->quantity }}
                    @endif
                </td>
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
                    <p><strong>Category:</strong> {{ optional($item->inventoryCategory)->name }}</p>
                    <p><strong>Department:</strong> {{ $item->department }}</p>
                    <p><strong>Location:</strong> {{ $item->location }}</p>
                    <p><strong>Supplier:</strong> {{ $item->supplier }}</p>
                    <p><strong>Purchase Date:</strong> {{ optional($item->purchase_date)->format('Y-m-d') }}</p>
                    <p><strong>Quantity:</strong> {{ $item->quantity }}</p>
                    <p><strong>Minimum Stock:</strong> {{ $item->minimum_stock }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($item->status) }}</p>

                    <form action="{{ route('inventory.issue', $item) }}" method="POST" class="row g-2 mb-2">
                        @csrf
                        <div class="col-auto">
                            <input type="number" name="quantity" min="1" value="1" class="form-control form-control-sm" aria-label="Quantity to issue">
                        </div>
                        <div class="col-auto">
                            <input type="text" name="purpose" class="form-control form-control-sm" placeholder="Purpose" aria-label="Purpose">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-warning btn-sm">Issue</button>
                        </div>
                    </form>
                    <form action="{{ route('inventory.return', $item) }}" method="POST" class="row g-2">
                        @csrf
                        <div class="col-auto">
                            <input type="number" name="quantity" min="1" value="1" class="form-control form-control-sm" aria-label="Quantity to return">
                        </div>
                        <div class="col-auto">
                            <input type="text" name="purpose" class="form-control form-control-sm" placeholder="Purpose" aria-label="Purpose">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-success btn-sm">Return</button>
                        </div>
                    </form>

                    @include('inventory_transactions._list', ['transactions' => $item->transactions])

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
