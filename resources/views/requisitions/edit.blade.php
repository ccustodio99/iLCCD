@extends('layouts.app')

@section('title', 'Edit Requisition')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Requisition</h1>
    <form action="{{ route('requisitions.update', $requisition) }}" method="POST">
        @csrf
        @method('PUT')
        <div id="items-container">
            @foreach(old('item', $requisition->items->pluck('item')->toArray()) as $i => $name)
            <div class="row g-2 mb-3 item-row">
                <div class="col-md-5">
                    <label class="form-label">Item</label>
                    <input type="text" name="item[]" class="form-control" value="{{ old('item.'.$i, $requisition->items[$i]->item ?? '') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity[]" class="form-control" value="{{ old('quantity.'.$i, $requisition->items[$i]->quantity ?? 1) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Specification</label>
                    <input type="text" name="specification[]" class="form-control" value="{{ old('specification.'.$i, $requisition->items[$i]->specification ?? '') }}">
                </div>
            </div>
            @endforeach
        </div>
        <button type="button" id="add-item" class="btn btn-secondary mb-3">Add Item</button>
        <div class="mb-3">
            <label class="form-label">Purpose</label>
            <textarea name="purpose" class="form-control" rows="3" required>{{ old('purpose', $requisition->purpose) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                @php($statuses = ['pending_head' => 'Pending Head', 'approved' => 'Approved'])
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" {{ old('status', $requisition->status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
    @include('audit_trails._list', ['logs' => $requisition->auditTrails])
</div>
<script>
document.getElementById('add-item').addEventListener('click', function () {
    const container = document.getElementById('items-container');
    const row = container.querySelector('.item-row').cloneNode(true);
    row.querySelectorAll('input').forEach(input => input.value = input.type === 'number' ? 1 : '');
    container.appendChild(row);
});
</script>
@endsection
