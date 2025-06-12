@extends('layouts.app')

@section('title', 'New Ticket')

@section('content')
<div class="container">
    <h1 class="mb-4">New Ticket</h1>
    <form action="{{ route('tickets.store') }}" method="POST" class="ticket-form">
        @csrf
        <div class="mb-3">
            <label class="form-label">Category</label>
            <input type="hidden" name="ticket_category_id" id="ticket_category_id" value="{{ old('ticket_category_id') }}" required>
            <div class="d-flex flex-wrap gap-2 mb-2">
                @foreach($categories as $cat)
                    <button type="button" class="btn btn-outline-primary btn-lg category-btn" data-bs-toggle="collapse" data-bs-target="#cat-{{ $cat->id }}" aria-expanded="{{ $cat->children->contains('id', old('ticket_category_id')) ? 'true' : 'false' }}">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>
            @foreach($categories as $cat)
                <div id="cat-{{ $cat->id }}" class="collapse category-collapse mb-3 {{ $cat->children->contains('id', old('ticket_category_id')) ? 'show' : '' }}">
                    <div class="card card-body">
                        <select class="form-select subcategory-select" data-cat-id="{{ $cat->id }}">
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
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
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
        <button type="submit" class="btn btn-primary me-2">Submit</button>
        <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
    @include('partials.category-collapse-script')
</div>
@endsection
