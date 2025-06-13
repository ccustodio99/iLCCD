@extends('layouts.app')

@section('title', 'Edit Job Order')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Job Order</h1>
    <form action="{{ route('job-orders.update', $jobOrder) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="type_parent" id="type_parent" class="form-select" required>
                <option value="">Select Type</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}" {{ old('type_parent', $parentId) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Sub Type</label>
            <select name="job_type" id="job_type" class="form-select" required disabled>
                <option value="">Select Sub Type</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" required>{{ old('description', $jobOrder->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Attachment</label>
            <input type="file" name="attachment" class="form-control">
            @if($jobOrder->attachment_path)
                <small class="text-muted">Current: <a href="{{ route('job-orders.attachment', $jobOrder) }}" target="_blank">Download</a></small>
            @endif
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                @php($statuses = [
                    'pending_head' => 'Pending Head',
                    'pending_president' => 'Pending President',
                    'pending_finance' => 'Pending Finance',
                    'approved' => 'Approved',
                    'assigned' => 'Assigned',
                    'in_progress' => 'In Progress',
                    'completed' => 'Completed',
                    'closed' => 'Closed'
                ])
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" {{ old('status', $jobOrder->status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="{{ route('job-orders.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const parent = document.getElementById('type_parent');
    const child = document.getElementById('job_type');

    function loadChildren(id, selected) {
        child.innerHTML = '<option value="">Select Sub Type</option>';
        if (!id) {
            child.disabled = true;
            return;
        }
        child.disabled = false;
        fetch(`/job-order-types/${id}/children`)
            .then(r => r.json())
            .then(data => {
                data.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.name;
                    opt.textContent = c.name;
                    if (selected === c.name) opt.selected = true;
                    child.appendChild(opt);
                });
            });
    }

    parent.addEventListener('change', () => loadChildren(parent.value));
    loadChildren(parent.value, '{{ old('job_type', $jobOrder->job_type) }}');
});
</script>
@endsection
