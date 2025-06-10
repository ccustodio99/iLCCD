@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Purchase Order</h1>
    <form action="{{ route('purchase-orders.update', $purchaseOrder) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Requisition ID</label>
            <input type="number" name="requisition_id" class="form-control" value="{{ old('requisition_id', $purchaseOrder->requisition_id) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Inventory Item ID</label>
            <input type="number" name="inventory_item_id" class="form-control" value="{{ old('inventory_item_id', $purchaseOrder->inventory_item_id) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Item</label>
            <input type="text" name="item" class="form-control" value="{{ old('item', $purchaseOrder->item) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $purchaseOrder->quantity) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <input type="text" name="status" class="form-control" value="{{ old('status', $purchaseOrder->status) }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
