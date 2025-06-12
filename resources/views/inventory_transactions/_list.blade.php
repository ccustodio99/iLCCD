@if($transactions->isNotEmpty())
    <h6 class="mt-3">Recent Transactions</h6>
    <ul class="list-group mb-3">
        @foreach($transactions as $tx)
            <li class="list-group-item">
                <div class="d-flex justify-content-between">
                    <span>{{ $tx->created_at->format('Y-m-d H:i') }}</span>
                    <span>{{ $tx->user?->name ?? 'System' }}</span>
                    <span>{{ ucfirst($tx->action) }}</span>
                    <span>{{ $tx->quantity }}</span>
                    <span>{{ $tx->purpose ?? '-' }}</span>
                </div>
            </li>
        @endforeach
    </ul>
@endif
