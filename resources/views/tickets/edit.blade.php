@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Ticket</h1>
    <form action="{{ route('tickets.update', $ticket) }}" method="POST">
        @csrf
        @method('PUT')
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
            <label class="form-label">Status</label>
            <input type="text" name="status" class="form-control" value="{{ old('status', $ticket->status) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Due Date</label>
            <input type="date" name="due_at" class="form-control" value="{{ old('due_at', optional($ticket->due_at)->format('Y-m-d')) }}">
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
