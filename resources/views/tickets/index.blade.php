@extends('layouts.app')

@section('title', 'Tickets')

@section('content')
<div class="container">
    <h1 class="mb-4">My Tickets</h1>
    @include('components.per-page-selector')
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#newTicketModal">New Ticket</button>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">Tickets</caption>
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
                <td>{{ $ticket->ticketCategory->name }}</td>
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

    <div class="modal fade" id="newTicketModal" tabindex="-1" aria-labelledby="newTicketModalLabel" aria-hidden="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="newTicketModalLabel" class="modal-title">New Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data" class="ticket-form">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="modal_ticket_category_id">Category</label>
                            <input type="hidden" name="ticket_category_id" id="modal_ticket_category_id" value="{{ old('ticket_category_id') }}" required>
                            <div class="d-flex flex-wrap gap-2 mb-2">
                                @foreach($categories as $cat)
                                    <button type="button" class="btn btn-outline-primary btn-lg category-btn" data-bs-toggle="collapse" data-bs-target="#new-cat-{{ $cat->id }}" aria-expanded="{{ $cat->children->contains('id', old('ticket_category_id')) ? 'true' : 'false' }}">
                                        {{ $cat->name }}
                                    </button>
                                @endforeach
                            </div>
                            @foreach($categories as $cat)
                                <div id="new-cat-{{ $cat->id }}" class="collapse category-collapse mb-3 {{ $cat->children->contains('id', old('ticket_category_id')) ? 'show' : '' }}">
                                    <div class="card card-body">
                                        <select id="new_subcategory_select_{{ $cat->id }}" class="form-select subcategory-select" data-cat-id="{{ $cat->id }}">
                                            <option value="">Select {{ $cat->name }} option</option>
                                            @foreach($cat->children as $child)
                                                <option value="{{ $child->id }}" {{ old('ticket_category_id') == $child->id ? 'selected' : '' }}>{{ $child->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="new_subject">Subject</label>
                            <input id="new_subject" type="text" name="subject" class="form-control" value="{{ old('subject') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="new_description">Description</label>
                            <textarea id="new_description" name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="new_attachment">Attachment</label>
                            <input id="new_attachment" type="file" name="attachment" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="new_assigned_to_id">Assign To</label>
                            <select id="new_assigned_to_id" name="assigned_to_id" class="form-select">
                                <option value="">Unassigned</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ old('assigned_to_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="new_watchers">Watchers</label>
                            <select id="new_watchers" name="watchers[]" class="form-select" multiple>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ collect(old('watchers'))->contains($u->id) ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hold Ctrl or Command to select multiple users</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="new_due_at">Due Date</label>
                            <input id="new_due_at" type="date" name="due_at" class="form-control" value="{{ old('due_at') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($tickets as $ticket)
    <div class="modal fade" id="ticketModal{{ $ticket->id }}" tabindex="-1" aria-labelledby="ticketModalLabel{{ $ticket->id }}" aria-hidden="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="ticketModalLabel{{ $ticket->id }}" class="modal-title">Ticket Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Category:</strong> {{ $ticket->ticketCategory->name }}</p>
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
                            <textarea id="comment{{ $ticket->id }}" name="comment" class="form-control mb-2" rows="2" required></textarea>
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
    <div class="modal fade" id="editTicketModal{{ $ticket->id }}" tabindex="-1" aria-labelledby="editTicketModalLabel{{ $ticket->id }}" aria-hidden="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="editTicketModalLabel{{ $ticket->id }}" class="modal-title">Edit Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tickets.update', $ticket) }}" method="POST" enctype="multipart/form-data" class="ticket-form">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="modal_ticket_category_id_edit{{ $ticket->id }}">Category</label>
                            <input type="hidden" name="ticket_category_id" id="modal_ticket_category_id_edit{{ $ticket->id }}" value="{{ old('ticket_category_id', $ticket->ticket_category_id) }}" class="ticket_category_id_input" required>
                            <div class="d-flex flex-wrap gap-2 mb-2">
                                @foreach($categories as $cat)
                                    <button type="button" class="btn btn-outline-primary btn-lg category-btn" data-bs-toggle="collapse" data-bs-target="#edit{{ $ticket->id }}-cat-{{ $cat->id }}" aria-expanded="{{ $cat->children->contains('id', old('ticket_category_id', $ticket->ticket_category_id)) ? 'true' : 'false' }}">
                                        {{ $cat->name }}
                                    </button>
                                @endforeach
                            </div>
                            @foreach($categories as $cat)
                                <div id="edit{{ $ticket->id }}-cat-{{ $cat->id }}" class="collapse category-collapse mb-3 {{ $cat->children->contains('id', old('ticket_category_id', $ticket->ticket_category_id)) ? 'show' : '' }}">
                                    <div class="card card-body">
                                        <select id="edit_subcategory_select_{{ $ticket->id }}_{{ $cat->id }}" class="form-select subcategory-select" data-cat-id="{{ $cat->id }}">
                                            <option value="">Select {{ $cat->name }} option</option>
                                            @foreach($cat->children as $child)
                                                <option value="{{ $child->id }}" {{ old('ticket_category_id', $ticket->ticket_category_id) == $child->id ? 'selected' : '' }}>{{ $child->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="subject_edit{{ $ticket->id }}">Subject</label>
                            <input id="subject_edit{{ $ticket->id }}" type="text" name="subject" class="form-control" value="{{ old('subject', $ticket->subject) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="description_edit{{ $ticket->id }}">Description</label>
                            <textarea id="description_edit{{ $ticket->id }}" name="description" class="form-control" rows="4" required>{{ old('description', $ticket->description) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="attachment_edit{{ $ticket->id }}">Attachment</label>
                            <input id="attachment_edit{{ $ticket->id }}" type="file" name="attachment" class="form-control">
                            @if($ticket->attachment_path)
                                <small class="text-muted">Current: <a href="{{ route('tickets.attachment', $ticket) }}" target="_blank">Download</a></small>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="assigned_to_id_edit{{ $ticket->id }}">Assign To</label>
                            <select id="assigned_to_id_edit{{ $ticket->id }}" name="assigned_to_id" class="form-select">
                                <option value="">Unassigned</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ old('assigned_to_id', $ticket->assigned_to_id) == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="watchers_edit{{ $ticket->id }}">Watchers</label>
                            <select id="watchers_edit{{ $ticket->id }}" name="watchers[]" class="form-select" multiple>
                                @php($selected = old('watchers', $ticket->watchers->pluck('id')->toArray()))
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ in_array($u->id, $selected) ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hold Ctrl or Command to select multiple users</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="status_edit{{ $ticket->id }}">Status</label>
                            <select id="status_edit{{ $ticket->id }}" name="status" class="form-select" required>
                                @php($statuses = ['open' => 'Open', 'escalated' => 'Escalated', 'converted' => 'Converted', 'closed' => 'Closed'])
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $ticket->status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="due_at_edit{{ $ticket->id }}">Due Date</label>
                            <input id="due_at_edit{{ $ticket->id }}" type="date" name="due_at" class="form-control" value="{{ old('due_at', optional($ticket->due_at)->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
    @include('partials.category-collapse-script')
</div>
@endsection
