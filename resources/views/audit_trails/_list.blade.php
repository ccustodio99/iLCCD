@if($logs->isNotEmpty())
    <h6 class="mt-3">Audit Trail</h6>
    <ul class="list-group mb-3">
        @foreach($logs as $log)
            <li class="list-group-item">
                <div class="d-flex justify-content-between">
                    <span>{{ $log->created_at->format('Y-m-d H:i') }}</span>
                    <span>{{ $log->user?->name ?? 'System' }}</span>
                    <span>{{ ucfirst($log->action) }}</span>
                </div>
                @if($log->changes)
                    <ul class="list-unstyled small mb-0 mt-1">
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
            </li>
        @endforeach
    </ul>
@endif
