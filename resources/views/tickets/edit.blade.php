@extends('layouts.app')

@section('title', 'Edit Ticket')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Ticket</h1>
    <form action="{{ route('tickets.update', $ticket) }}" method="POST" class="ticket-form">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label" for="ticket_category_id">Category</label>
            <input type="hidden" name="ticket_category_id" id="ticket_category_id" value="{{ old('ticket_category_id', $ticket->ticket_category_id) }}" required>
            <div class="d-flex flex-wrap gap-2 mb-2">
                @foreach($categories as $cat)
                    <button type="button" class="btn btn-outline-primary btn-lg category-btn" data-bs-toggle="collapse" data-bs-target="#cat-{{ $cat->id }}" aria-expanded="{{ $cat->children->contains('id', old('ticket_category_id', $ticket->ticket_category_id)) ? 'true' : 'false' }}">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>
            @foreach($categories as $cat)
                <div id="cat-{{ $cat->id }}" class="collapse category-collapse mb-3 {{ $cat->children->contains('id', old('ticket_category_id', $ticket->ticket_category_id)) ? 'show' : '' }}">
                    <div class="card card-body">
                        <select id="subcategory_select_{{ $cat->id }}" class="form-select subcategory-select" data-cat-id="{{ $cat->id }}">
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
            <label class="form-label" for="subject">Subject</label>
            <input id="subject" type="text" name="subject" class="form-control" value="{{ old('subject', $ticket->subject) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label" for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="4" required>{{ old('description', $ticket->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label" for="assigned_to_id">Assign To</label>
            <select id="assigned_to_id" name="assigned_to_id" class="form-select">
                <option value="">Unassigned</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ old('assigned_to_id', $ticket->assigned_to_id) == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label" for="watchers">Watchers</label>
            <select id="watchers" name="watchers[]" class="form-select" multiple>
                @php($selected = old('watchers', $ticket->watchers->pluck('id')->toArray()))
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ in_array($u->id, $selected) ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
            <small class="text-muted">Hold Ctrl or Command to select multiple users</small>
        </div>
        <div class="mb-3">
            <label class="form-label" for="status">Status</label>
            <select id="status" name="status" class="form-select" required>
                @php($statuses = ['open' => 'Open', 'escalated' => 'Escalated', 'converted' => 'Converted', 'closed' => 'Closed'])
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" {{ old('status', $ticket->status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label" for="due_at">Due Date</label>
            <input id="due_at" type="date" name="due_at" class="form-control" value="{{ old('due_at', optional($ticket->due_at)->format('Y-m-d')) }}">
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
    @include('partials.category-collapse-script')
</div>
@endsection
