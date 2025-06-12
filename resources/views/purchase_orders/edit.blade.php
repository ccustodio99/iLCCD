@extends('layouts.app')

@section('title', 'Edit Purchase Order')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Purchase Order</h1>
    <form action="{{ route('purchase-orders.update', $purchaseOrder) }}" method="POST" enctype="multipart/form-data">
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
            <label class="form-label">Supplier</label>
            <input type="text" name="supplier" class="form-control" value="{{ old('supplier', $purchaseOrder->supplier) }}">
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
            <label class="form-label">Attachment</label>
            <input type="file" name="attachment" class="form-control">
            @if($purchaseOrder->attachment_path)
                <small class="text-muted">Current: <a href="{{ route('purchase-orders.attachment', $purchaseOrder) }}" target="_blank">Download</a></small>
            @endif
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                @php($statuses = ['draft' => 'Draft', 'ordered' => 'Ordered', 'received' => 'Received'])
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" {{ old('status', $purchaseOrder->status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
    @include('audit_trails._list', ['logs' => $purchaseOrder->auditTrails])
</div>
@endsection
