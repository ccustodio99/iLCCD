@extends('layouts.app')

@section('title', 'New Ticket')

@section('content')
<div class="container">
    @include('partials.ticket-categories-script', ['categories' => $categories])
    <h1 class="mb-4">New Ticket</h1>
    <form action="{{ route('tickets.store') }}" method="POST" class="ticket-form">
        @csrf
        @php
            $selectedCategory = null;
            $selectedSub = old('ticket_category_id');
            foreach ($categories as $cat) {
                if ($cat->children->contains('id', $selectedSub)) {
                    $selectedCategory = $cat->id;
                    break;
                }
            }
        @endphp
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select class="form-select category-select mb-2" required>
                <option value="">Select Category</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ (string)$selectedCategory === (string)$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="ticket_category_id" class="form-select subcategory-select" data-selected="{{ $selectedSub }}" required></select>
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
            <label class="form-label">Due Date</label>
            <input type="date" name="due_at" class="form-control" value="{{ old('due_at') }}">
        </div>
        <button type="submit" class="btn btn-primary me-2">Submit</button>
        <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
    @include('partials.category-dropdown-script')
</div>
@endsection
