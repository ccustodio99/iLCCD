@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">New Job Order</h1>
    <form action="{{ route('job-orders.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Type</label>
            <input type="text" name="job_type" class="form-control" value="{{ old('job_type') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
