@extends('layouts.app')

@section('title', 'Edit Inventory Item')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Inventory Item</h1>
    <form action="{{ route('inventory.update', $inventoryItem) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $inventoryItem->name) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $inventoryItem->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="inventory_category_id" class="form-select">
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('inventory_category_id', $inventoryItem->inventory_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Department</label>
            <input type="text" name="department" class="form-control" value="{{ old('department', $inventoryItem->department) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" value="{{ old('location', $inventoryItem->location) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Supplier</label>
            <input type="text" name="supplier" class="form-control" value="{{ old('supplier', $inventoryItem->supplier) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Purchase Date</label>
            <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date', optional($inventoryItem->purchase_date)->format('Y-m-d')) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $inventoryItem->quantity) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Minimum Stock</label>
            <input type="number" name="minimum_stock" class="form-control" value="{{ old('minimum_stock', $inventoryItem->minimum_stock) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                @php($statuses = [
                    \App\Models\InventoryItem::STATUS_AVAILABLE => 'Available',
                    \App\Models\InventoryItem::STATUS_RESERVED => 'Reserved',
                    \App\Models\InventoryItem::STATUS_MAINTENANCE => 'Maintenance',
                    \App\Models\InventoryItem::STATUS_RETIRED => 'Retired',
                ])
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" {{ old('status', $inventoryItem->status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary me-2">Update</button>
        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
    @include('audit_trails._list', ['logs' => $inventoryItem->auditTrails])
</div>
@endsection
