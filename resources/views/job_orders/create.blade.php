@extends('layouts.app')

@section('title', 'New Job Order')

@section('content')
<div class="container">
    <h1 class="mb-4">New Job Order</h1>
    <form action="{{ route('job-orders.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="type_parent" id="type_parent" class="form-select" required>
                <option value="">Select Type</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}" {{ old('type_parent') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
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
            <textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Attachment</label>
            <input type="file" name="attachment" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary me-2">Submit</button>
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
                if (data.length === 0) {
                    const name = parent.options[parent.selectedIndex].text;
                    const opt = document.createElement('option');
                    opt.value = name;
                    opt.textContent = name;
                    opt.selected = true;
                    child.appendChild(opt);
                    return;
                }
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
    loadChildren(parent.value, '{{ old('job_type') }}');
});
</script>
@endsection
