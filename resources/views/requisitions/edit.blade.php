@extends('layouts.app')

@section('title', 'Edit Requisition')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Requisition</h1>
    @if($requisition->ticket_id)
        <p><strong>Ticket ID:</strong>
            <a href="{{ route('tickets.index') }}#ticketModal{{ $requisition->ticket_id }}">
                {{ $requisition->ticket_id }}
            </a>
        </p>
    @endif
    <form action="{{ route('requisitions.update', $requisition) }}" method="POST" enctype="multipart/form-data">
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
                    <label class="form-label">SKU</label>
                    <input type="text" name="sku[]" class="form-control" value="{{ old('sku.'.$i, $requisition->items[$i]->sku ?? '') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity[]" class="form-control" value="{{ old('quantity.'.$i, $requisition->items[$i]->quantity ?? 1) }}" required>
                </div>
                <div class="col-md-2">
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
            <label class="form-label">Remarks</label>
            <textarea name="remarks" class="form-control" rows="2">{{ old('remarks', $requisition->remarks) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Attachment</label>
            <input type="file" name="attachment" class="form-control">
            @if($requisition->attachment_path)
                <small class="text-muted">Current: <a href="{{ route('requisitions.attachment', $requisition) }}" target="_blank">Download</a></small>
            @endif
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                @php($statuses = [
                    \App\Models\Requisition::STATUS_PENDING_HEAD => 'Pending Head',
                    \App\Models\Requisition::STATUS_APPROVED => 'Approved',
                ])
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" {{ old('status', $requisition->status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="{{ route('requisitions.index') }}" class="btn btn-secondary">Cancel</a>
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
