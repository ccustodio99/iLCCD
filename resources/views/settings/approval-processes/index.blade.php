@extends('layouts.app')

@section('title', 'Approval Processes')

@section('content')
<div class="container">
    @include('components.breadcrumbs', ['links' => [
        ['label' => 'Settings', 'url' => route('settings.index')],
        ['label' => 'Approval Processes']
    ]])
    <h1 class="mb-4">Approval Processes</h1>
    @include('components.per-page-selector')
    <a href="{{ route('approval-processes.create') }}" class="btn btn-sm btn-primary mb-3">Add Process</a>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">Approval Processes</caption>
        <thead>
            <tr>
                <th>Module</th>
                <th>Department</th>
                <th>Stages</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($processes as $process)
            <tr>
                <td>{{ \App\Models\ApprovalProcess::MODULES[$process->module] ?? $process->module }}</td>
                <td>{{ $process->department }}</td>
                <td>
                    @foreach($process->stages->sortBy('position') as $stage)
                        <div>{{ $stage->position }}. {{ $stage->name }}</div>
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('approval-processes.edit', $process) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('approval-processes.destroy', $process) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this process?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    {{ $processes->links() }}
</div>
@endsection
