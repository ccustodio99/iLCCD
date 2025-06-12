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
            <select name="job_type" class="form-select" required>
                @foreach($types as $type)
                    <option value="{{ $type }}" {{ old('job_type', $jobOrder->job_type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
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
@endsection
