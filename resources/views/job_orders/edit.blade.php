@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Job Order</h1>
    <form action="{{ route('job-orders.update', $jobOrder) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Type</label>
            <input type="text" name="job_type" class="form-control" value="{{ old('job_type', $jobOrder->job_type) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" required>{{ old('description', $jobOrder->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <input type="text" name="status" class="form-control" value="{{ old('status', $jobOrder->status) }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
