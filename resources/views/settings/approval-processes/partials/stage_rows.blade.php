@foreach($approvalProcess->stages->sortBy('position') as $stage)
<tr class="stage-row">
    <form action="{{ route('approval-processes.stages.update', [$approvalProcess, $stage]) }}" method="POST" class="stage-update-form">
        @csrf
        @method('PUT')
        <td><input type="number" name="position" class="form-control" value="{{ $stage->position }}" aria-label="Position" required></td>
        <td><input type="text" name="name" class="form-control" value="{{ $stage->name }}" aria-label="Stage name" required></td>
        <td>
            <select name="assigned_user_id" class="form-select" aria-label="Assigned user">
                <option value="">-- None --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $stage->assigned_user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </td>
        <td class="whitespace-nowrap">
            <button type="submit" class="btn btn-sm btn-primary me-1">Update</button>
    </form>
            <form action="{{ route('approval-processes.stages.destroy', [$approvalProcess, $stage]) }}" method="POST" class="d-inline stage-delete-form" onsubmit="return confirm('Delete this stage?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
            </form>
        </td>
</tr>
@endforeach
<tr>
    <form action="{{ route('approval-processes.stages.store', $approvalProcess) }}" method="POST" class="stage-form">
        @csrf
        <td><input type="number" name="position" class="form-control" value="{{ $approvalProcess->stages->max('position') + 1 }}" aria-label="Position" required></td>
        <td><input type="text" name="name" class="form-control" aria-label="Stage name" required></td>
        <td>
            <select name="assigned_user_id" class="form-select" aria-label="Assigned user">
                <option value="">-- None --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </td>
        <td><button type="submit" class="btn btn-sm btn-success">Add</button></td>
    </form>
</tr>
