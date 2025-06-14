@extends('layouts.app')

@section('title', 'Audit Trail')

@section('content')
<div class="container">
    <h1 class="mb-4">Audit Trail</h1>
    @include('components.per-page-selector', ['default' => 20])
    <div class="mb-3">
        <form method="GET" class="row row-cols-lg-auto g-2 align-items-end">
            <div class="col">
                <label for="filter-user" class="form-label">User</label>
                <select id="filter-user" name="user_id" class="form-select">
                    <option value="">Any</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" @selected((string)request('user_id') === (string)$u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="filter-module" class="form-label">Module</label>
                <select id="filter-module" name="auditable_type" class="form-select">
                    <option value="">Any</option>
                    @foreach($modules as $module)
                        <option value="{{ $module }}" @selected(request('auditable_type') === $module)>{{ class_basename($module) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="filter-action" class="form-label">Action</label>
                <select id="filter-action" name="action" class="form-select">
                    <option value="">Any</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" @selected(request('action') === $action)>{{ ucfirst(str_replace('_', ' ', $action)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="filter-from" class="form-label">From</label>
                <input id="filter-from" type="date" name="from" value="{{ request('from') }}" class="form-control">
            </div>
            <div class="col">
                <label for="filter-to" class="form-label">To</label>
                <input id="filter-to" type="date" name="to" value="{{ request('to') }}" class="form-control">
            </div>
            <div class="col">
                <button type="submit" class="btn btn-secondary">Filter</button>
            </div>
        </form>
    </div>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">Audit Trail</caption>
        <thead>
            <tr>
                <th>Date</th>
                <th>User</th>
                <th>Model</th>
                <th>Action</th>
                <th>Changes</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
            <tr>
                <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $log->user?->name ?? 'System' }}</td>
                <td>{{ class_basename($log->auditable_type) }}#{{ $log->auditable_id }}</td>
                <td>{{ $log->action }}</td>
                <td>
                    @if($log->changes)
                        <ul class="list-unstyled mb-0">
                            @foreach($log->changes as $field => $values)
                                @php
                                    $label = $field;
                                    $old = $values['old'];
                                    $new = $values['new'];
                                    if ($field === 'assigned_to_id') {
                                        $label = 'assigned_to';
                                        $old = \App\Models\User::find($values['old'])?->name;
                                        $new = \App\Models\User::find($values['new'])?->name;
                                    }
                                @endphp
                                <li>{{ $label }}: {{ $old }} → {{ $new }}</li>
                            @endforeach
                        </ul>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    {{ $logs->links() }}
</div>
@endsection
