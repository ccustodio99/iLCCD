@extends('layouts.app')

@section('title', 'Approval Process Details')

@section('content')
<div class="container">
    @include('components.breadcrumbs', ['links' => [
        ['label' => 'Settings', 'url' => route('settings.index')],
        ['label' => 'Approval Processes', 'url' => route('approval-processes.index')],
        ['label' => 'View']
    ]])
    <h1 class="mb-4">Approval Process Details</h1>
    <div class="mb-3"><strong>Module:</strong> {{ $approvalProcess->module }}</div>
    <div class="mb-3"><strong>Department:</strong> {{ $approvalProcess->department }}</div>
    <h2 class="h4 mb-3">Stages</h2>
    <div class="table-responsive">
        <table class="table table-bordered">
            <caption class="visually-hidden">Approval Stages</caption>
            <thead>
                <tr>
                    <th>Position</th>
                    <th>Name</th>
                    <th>Assigned User</th>
                </tr>
            </thead>
            <tbody>
                @foreach($approvalProcess->stages->sortBy('position') as $stage)
                <tr>
                    <td>{{ $stage->position }}</td>
                    <td>{{ $stage->name }}</td>
                    <td>{{ $stage->assignedUser?->name ?? '-- None --' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <a href="{{ route('approval-processes.index') }}" class="btn btn-secondary">Back</a>
    <a href="{{ route('approval-processes.edit', $approvalProcess) }}" class="btn btn-primary">Edit</a>
</div>
@endsection
