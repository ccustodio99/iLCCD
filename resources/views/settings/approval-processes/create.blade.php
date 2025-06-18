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
        <h2 class="h4 mb-3">Stages</h2>
        <div class="table-responsive mb-3">
            <table class="table table-bordered align-middle">
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
                    <tr class="stage-row">
                        <td><input type="number" name="stages[0][position]" class="form-control" value="1" required></td>
                        <td><input type="text" name="stages[0][name]" class="form-control" required></td>
                        <td>
                            <select name="stages[0][assigned_user_id]" class="form-select">
                                <option value="">-- None --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><button type="button" class="btn btn-sm btn-danger remove-stage">Delete</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button type="button" id="add-stage" class="btn btn-secondary mb-3">Add Stage</button>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="{{ route('approval-processes.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<script>
document.getElementById('add-stage').addEventListener('click', function () {
    const container = document.getElementById('stages-body');
    const row = container.querySelector('.stage-row').cloneNode(true);
    const index = container.querySelectorAll('.stage-row').length;
    row.querySelectorAll('input, select').forEach(el => {
        const name = el.getAttribute('name');
        if (name) {
            el.setAttribute('name', name.replace(/\d+/, index));
        }
        if (el.tagName === 'INPUT' && el.type === 'number') {
            el.value = index + 1;
        } else {
            el.value = '';
        }
    });
    container.appendChild(row);
});

document.getElementById('stages-body').addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-stage')) {
        const rows = this.querySelectorAll('.stage-row');
        if (rows.length > 1) {
            e.target.closest('tr').remove();
            this.querySelectorAll('.stage-row').forEach((row, idx) => {
                row.querySelectorAll('input, select').forEach(el => {
                    const name = el.getAttribute('name');
                    if (name) {
                        el.setAttribute('name', name.replace(/\d+/, idx));
                    }
                    if (el.tagName === 'INPUT' && el.type === 'number') {
                        el.value = idx + 1;
                    }
                });
            });
        }
    }
});

document.querySelector('form').addEventListener('submit', function(e) {
    if (document.querySelectorAll('#stages-body .stage-row').length === 0) {
        e.preventDefault();
        alert('At least one stage is required.');
    }
});
</script>
@endsection
