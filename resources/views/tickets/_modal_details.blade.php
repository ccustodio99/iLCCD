<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 id="ticketModalLabel{{ $ticket->id }}" class="modal-title">Ticket Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <p><strong>Category:</strong> {{ optional($ticket->ticketCategory->parent)->name ?? 'N/A' }}</p>
            <p><strong>Sub Category:</strong> {{ optional($ticket->ticketCategory)->name ?? 'N/A' }}</p>
            <p><strong>Subject:</strong> {{ $ticket->formatted_subject }}</p>
            <p><strong>Description:</strong> {{ $ticket->description }}</p>
            @if($ticket->attachment_path)
                <p><strong>Attachment:</strong> <a href="{{ route('tickets.attachment', $ticket) }}" target="_blank">Download</a></p>
            @endif
            <p><strong>Status:</strong> {{ ucfirst($ticket->status) }}</p>
            <p><strong>Assigned To:</strong> {{ optional($ticket->assignedTo)->name ?? 'Unassigned' }}</p>
            <p><strong>Watchers:</strong>
                @foreach($ticket->watchers as $w)
                    <span class="badge bg-secondary">{{ $w->name }}</span>
                @endforeach
            </p>
            <p><strong>Created:</strong> {{ $ticket->created_at->format('Y-m-d H:i') }}</p>
            <p><strong>Updated:</strong> {{ $ticket->updated_at->format('Y-m-d H:i') }}</p>
            <p><strong>Escalated:</strong> {{ optional($ticket->escalated_at)->format('Y-m-d H:i') }}</p>
            <p><strong>Resolved:</strong> {{ optional($ticket->resolved_at)->format('Y-m-d H:i') }}</p>
            <p><strong>Due:</strong> {{ optional($ticket->due_at)->format('Y-m-d H:i') }}</p>


            @if($ticket->jobOrder)
                <p><strong>Job Order ID:</strong> {{ $ticket->jobOrder->id }}</p>
            @endif

            @if($ticket->requisitions->count())
                <h6>Requisitions</h6>
                <ul>
                    @foreach($ticket->requisitions as $req)
                        <li>
                            <a href="{{ route('requisitions.edit', $req) }}">#{{ $req->id }}</a>
                            - {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                        </li>
                    @endforeach
                </ul>
            @endif

            @include('audit_trails._list', ['logs' => $ticket->auditTrails])

            @if($ticket->comments->isNotEmpty())
                <h6 class="mt-3">Comments</h6>
                <ul class="list-group mb-3">
                    @foreach($ticket->comments as $comment)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span>{{ $comment->created_at->format('Y-m-d H:i') }}</span>
                                <span>{{ $comment->user->name }}</span>
                            </div>
                            <p class="mb-0 mt-1">{{ $comment->comment }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif

            @if(auth()->id() === $ticket->user_id || auth()->id() === $ticket->assigned_to_id || $ticket->watchers->contains(auth()->id()))
                <form action="{{ route('tickets.comment', $ticket) }}" method="POST" class="mb-3">
                    @csrf
                    <textarea name="comment" class="form-control mb-2" rows="2" required></textarea>
                    <button type="submit" class="btn btn-primary btn-sm">Add Comment</button>
                </form>
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
</div>
