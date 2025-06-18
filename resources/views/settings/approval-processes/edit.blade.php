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
    <form action="{{ route('approval-processes.update', $approvalProcess) }}" method="POST" class="mb-4">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Module</label>
            <input type="text" name="module" class="form-control" value="{{ old('module', $approvalProcess->module) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Department</label>
            <input type="text" name="department" class="form-control" value="{{ old('department', $approvalProcess->department) }}" required>
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="{{ route('approval-processes.index') }}" class="btn btn-secondary">Cancel</a>
    </form>

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
            <tbody>
                @foreach($approvalProcess->stages->sortBy('position') as $stage)
                <tr>
                    <form action="{{ route('approval-processes.stages.update', [$approvalProcess, $stage]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <td><input type="number" name="position" class="form-control" value="{{ old('position', $stage->position) }}" required></td>
                        <td><input type="text" name="name" class="form-control" value="{{ old('name', $stage->name) }}" required></td>
                        <td>
                            <select name="assigned_user_id" class="form-select">
                                <option value="">-- None --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_user_id', $stage->assigned_user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="whitespace-nowrap">
                            <button type="submit" class="btn btn-sm btn-primary me-1">Update</button>
                            <button formaction="{{ route('approval-processes.stages.destroy', [$approvalProcess, $stage]) }}" formmethod="POST" class="btn btn-sm btn-danger" onclick="return confirm('Delete this stage?')">
                                @csrf
                                @method('DELETE')
                                Delete
                            </button>
                        </td>
                    </form>
                </tr>
                @endforeach
                <tr>
                    <form action="{{ route('approval-processes.stages.store', $approvalProcess) }}" method="POST">
                        @csrf
                        <td><input type="number" name="position" class="form-control" value="{{ old('position') }}" required></td>
                        <td><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></td>
                        <td>
                            <select name="assigned_user_id" class="form-select">
                                <option value="">-- None --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><button type="submit" class="btn btn-sm btn-success">Add</button></td>
                    </form>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
