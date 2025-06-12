@extends('layouts.app')

@section('title', 'New Job Order')

@section('content')
<div class="container">
    <h1 class="mb-4">New Job Order</h1>
    <form action="{{ route('job-orders.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="job_type" class="form-select" required>
                @foreach($types as $type)
                    <option value="{{ $type }}" {{ old('job_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
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
@endsection
