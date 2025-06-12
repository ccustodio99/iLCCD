@extends('layouts.app')

@section('title', 'Audit Trail')

@section('content')
<div class="container">
    <h1 class="mb-4">Audit Trail</h1>
    @include('components.per-page-selector', ['default' => 20])
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
