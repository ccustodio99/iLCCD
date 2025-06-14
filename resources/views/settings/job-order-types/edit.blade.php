@extends('layouts.app')

@section('title', 'Edit Job Order Type')

@section('content')
<div class="container">
    @include('components.breadcrumbs', ['links' => [
        ['label' => 'Settings', 'url' => route('settings.index')],
        ['label' => 'Job Order Types', 'url' => route('job-order-types.index')],
        ['label' => 'Edit']
    ]])
    <h1 class="mb-4">Edit Job Order Type</h1>
    <form action="{{ route('job-order-types.update', $jobOrderType) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $jobOrderType->name) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Parent Type</label>
            <select name="parent_id" class="form-select">
                <option value="">None</option>
                @foreach($parents as $parent)
                    <option value="{{ $parent->id }}" {{ old('parent_id', $jobOrderType->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $jobOrderType->is_active) ? 'checked' : '' }}>
            <label for="is_active" class="form-check-label">Active</label>
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="{{ route('job-order-types.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
