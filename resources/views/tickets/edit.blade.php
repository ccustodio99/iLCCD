@extends('layouts.app')

@section('title', 'Edit Ticket')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Ticket</h1>
    <form action="{{ route('tickets.update', $ticket) }}" method="POST" class="ticket-form">
        @csrf
        @method('PUT')
        @php
            $selectedSub = old('ticket_category_id', $ticket->ticket_category_id);
            $selectedCategory = null;
            foreach ($categories as $cat) {
                if ($cat->children->contains('id', $selectedSub)) {
                    $selectedCategory = $cat->id;
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
                    <option value="{{ $cat->id }}" {{ (string)$selectedCategory === (string)$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="ticket_category_id" class="form-select subcategory-select" data-selected="{{ $selectedSub }}" required></select>
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
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
    @include('partials.category-dropdown-script')
</div>
@endsection
