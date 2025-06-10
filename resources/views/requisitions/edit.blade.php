@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Requisition</h1>
    <form action="{{ route('requisitions.update', $requisition) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Item</label>
            <input type="text" name="item" class="form-control" value="{{ old('item', $requisition->item) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $requisition->quantity) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Specification</label>
            <textarea name="specification" class="form-control" rows="3">{{ old('specification', $requisition->specification) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Purpose</label>
            <textarea name="purpose" class="form-control" rows="3" required>{{ old('purpose', $requisition->purpose) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <input type="text" name="status" class="form-control" value="{{ old('status', $requisition->status) }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
