<div class="table-responsive">
<table class="table table-striped">
    <caption class="visually-hidden">Approval Processes</caption>
    <thead>
        <tr>
            <th>Module</th>
            <th>Department</th>
            <th>Stages</th>
            <th>Assigned Users</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($processes as $process)
        <tr>
            <td>{{ $process->module }}</td>
            <td>{{ $process->department }}</td>
            <td>
                @foreach($process->stages->sortBy('position') as $stage)
                    <div>{{ $stage->position }}. {{ $stage->name }}</div>
                @endforeach
            </td>
            <td>
                @foreach($process->stages->sortBy('position') as $stage)
                    <div>{{ $stage->assignedUser?->name ?? '-- None --' }}</div>
                @endforeach
            </td>
            <td>
                <a href="{{ route('approval-processes.show', $process) }}" class="btn btn-sm btn-info">View</a>
                <a href="{{ route('approval-processes.edit', $process) }}" class="btn btn-sm btn-primary">Edit</a>
                <form action="{{ route('approval-processes.destroy', $process) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this process?')">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">No approval processes found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>
{{ $processes->links() }}
