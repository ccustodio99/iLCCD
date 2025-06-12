@extends('layouts.app')

@section('title', 'Create Purchase Order')

@section('content')
<div class="container">
    <h1 class="mb-4">Create Purchase Order</h1>
    <form action="{{ route('purchase-orders.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Requisition ID</label>
            <input type="number" name="requisition_id" class="form-control" value="{{ old('requisition_id') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Inventory Item ID</label>
            <input type="number" name="inventory_item_id" class="form-control" value="{{ old('inventory_item_id') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Supplier</label>
            <input type="text" name="supplier" class="form-control" value="{{ old('supplier') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Item</label>
            <input type="text" name="item" class="form-control" value="{{ old('item') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" value="{{ old('quantity') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Attachment</label>
            <input type="file" name="attachment" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
