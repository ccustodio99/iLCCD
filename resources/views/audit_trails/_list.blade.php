@if($logs->isNotEmpty())
    <h6 class="mt-3">Audit Trail</h6>
    <ul class="list-group mb-3">
        @foreach($logs as $log)
            <li class="list-group-item d-flex justify-content-between">
                <span>{{ $log->created_at->format('Y-m-d H:i') }}</span>
                <span>{{ $log->user?->name ?? 'System' }}</span>
                <span>{{ ucfirst($log->action) }}</span>
            </li>
        @endforeach
    </ul>
@endif
