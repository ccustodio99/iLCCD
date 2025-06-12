@extends('layouts.app')

@section('title', 'Tickets')

@section('content')
<div class="container">
    <h1 class="mb-4">My Tickets</h1>
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#newTicketModal">New Ticket</button>
    <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Category</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Due</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
            <tr>
                <td>{{ $ticket->category }}</td>
                <td>{{ $ticket->formatted_subject }}</td>
                <td>{{ ucfirst($ticket->status) }}</td>
                <td>{{ optional($ticket->due_at)->format('Y-m-d') }}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#ticketModal{{ $ticket->id }}">Details</button>
                    <button type="button" class="btn btn-sm btn-primary ms-1" data-bs-toggle="modal" data-bs-target="#editTicketModal{{ $ticket->id }}">Edit</button>
                    <form action="{{ route('tickets.convert', $ticket) }}" method="POST" class="d-inline ms-1">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-secondary" onclick="return confirm('Convert to Job Order?')">Convert</button>
                    </form>
                    <form action="{{ route('tickets.requisition', $ticket) }}" method="POST" class="d-inline ms-1">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Convert to Requisition?')">Requisition</button>
                    </form>
                    <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="d-inline ms-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Archive this ticket?')">Archive</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>

    {{ $tickets->links() }}

    <div class="modal fade" id="newTicketModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control" value="{{ old('category') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Attachment</label>
                            <input type="file" name="attachment" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Assign To</label>
                            <select name="assigned_to_id" class="form-select">
                                <option value="">Unassigned</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ old('assigned_to_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Watchers</label>
                            <select name="watchers[]" class="form-select" multiple>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ collect(old('watchers'))->contains($u->id) ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hold Ctrl or Command to select multiple users</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_at" class="form-control" value="{{ old('due_at') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($tickets as $ticket)
    <div class="modal fade" id="ticketModal{{ $ticket->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ticket Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Category:</strong> {{ $ticket->category }}</p>
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
                        <p><strong>Job Order ID:</strong>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#jobOrderModal{{ $ticket->jobOrder->id }}">
                                {{ $ticket->jobOrder->id }}
                            </a>
                        </p>
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
        </div>
    </div>
    <div class="modal fade" id="editTicketModal{{ $ticket->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tickets.update', $ticket) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control" value="{{ old('category', $ticket->category) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" value="{{ old('subject', $ticket->subject) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required>{{ old('description', $ticket->description) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Attachment</label>
                            <input type="file" name="attachment" class="form-control">
                            @if($ticket->attachment_path)
                                <small class="text-muted">Current: <a href="{{ route('tickets.attachment', $ticket) }}" target="_blank">Download</a></small>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Assign To</label>
                            <select name="assigned_to_id" class="form-select">
                                <option value="">Unassigned</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ old('assigned_to_id', $ticket->assigned_to_id) == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Watchers</label>
                            <select name="watchers[]" class="form-select" multiple>
                                @php($selected = old('watchers', $ticket->watchers->pluck('id')->toArray()))
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ in_array($u->id, $selected) ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hold Ctrl or Command to select multiple users</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                @php($statuses = ['open' => 'Open', 'escalated' => 'Escalated', 'converted' => 'Converted', 'closed' => 'Closed'])
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $ticket->status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_at" class="form-control" value="{{ old('due_at', optional($ticket->due_at)->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
