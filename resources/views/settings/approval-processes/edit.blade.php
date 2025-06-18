@extends('layouts.app')

@section('title', 'Edit Approval Process')

@section('content')
<div class="container">
    @include('components.breadcrumbs', ['links' => [
        ['label' => 'Settings', 'url' => route('settings.index')],
        ['label' => 'Approval Processes', 'url' => route('approval-processes.index')],
        ['label' => 'Edit']
    ]])
    <h1 class="mb-4">Edit Approval Process</h1>
    <form id="process-form" action="{{ route('approval-processes.update', $approvalProcess) }}" method="POST" class="mb-4">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Module</label>
            <select name="module" class="form-select" required>
                @foreach($modules as $value => $label)
                    <option value="{{ $value }}" {{ old('module', $approvalProcess->module) == $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Department</label>
            <select name="department" class="form-select" required>
                @foreach($departments as $dept)
                    <option value="{{ $dept }}" {{ old('department', $approvalProcess->department) == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="{{ route('approval-processes.index') }}" class="btn btn-secondary">Cancel</a>
    </form>

    <h2 class="h4 mb-3">Stages</h2>
    <div class="table-responsive mb-3">
        <table id="stages-table" class="table table-bordered align-middle" data-stages-url="{{ route('approval-processes.stages.index', $approvalProcess) }}">
        <caption class="visually-hidden">Approval Stages</caption>
            <thead>
                <tr>
                    <th>Position</th>
                    <th>Name</th>
                    <th>Assigned User</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="stages-body">
                @include('settings.approval-processes.partials.stage_rows', ['approvalProcess' => $approvalProcess, 'users' => $users])
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
    @include('partials.approval-stages-script')
    <script>
        document.getElementById('process-form').addEventListener('submit', function(e) {
            if (document.querySelectorAll('#stages-body .stage-row').length === 0) {
                e.preventDefault();
                alert('At least one stage is required.');
            }
        });
    </script>
@endpush
