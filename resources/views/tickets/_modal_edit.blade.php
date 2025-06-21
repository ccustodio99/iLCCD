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
                @php
                    $editSub = old('ticket_category_id', $ticket->ticket_category_id);
                    $editCat = null;
                    foreach ($categories as $cat) {
                        if ($cat->children->contains('id', $editSub)) {
                            $editCat = $cat->id;
                            break;
                        }
                    }
                    $categoryData = $categories->mapWithKeys(function($cat) {
                        return [$cat->id => $cat->children->map(fn($c) => ['id' => $c->id, 'name' => $c->name])];
                    });
                @endphp
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select class="form-select category-select mb-2" data-categories='@json($categoryData)' required>
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (string)$editCat === (string)$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <select name="ticket_category_id" class="form-select subcategory-select" data-selected="{{ $editSub }}" required></select>
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
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        @php($statuses = [
                            'pending_head' => 'Pending Head',
                            'open' => 'Open',
                            'escalated' => 'Escalated',
                            'converted' => 'Converted',
                            'closed' => 'Closed',
                        ])
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
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
</div>
@include('partials.category-dropdown-script')
