@extends('layouts.app')

@section('title', 'Add Approval Process')

@section('content')
<div class="container">
    @include('components.breadcrumbs', ['links' => [
        ['label' => 'Settings', 'url' => route('settings.index')],
        ['label' => 'Approval Processes', 'url' => route('approval-processes.index')],
        ['label' => 'Add']
    ]])
    <h1 class="mb-4">Add Approval Process</h1>
    <form action="{{ route('approval-processes.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Module</label>
            <select name="module" class="form-select" required>
                @foreach($modules as $value => $label)
                    <option value="{{ $value }}" {{ old('module') == $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Department</label>
            <select name="department" class="form-select" required>
                @foreach($departments as $dept)
                    <option value="{{ $dept }}" {{ old('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="{{ route('approval-processes.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
