@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">New Requisition</h1>
    <form action="{{ route('requisitions.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Item</label>
            <input type="text" name="item" class="form-control" value="{{ old('item') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" value="{{ old('quantity', 1) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Specification</label>
            <textarea name="specification" class="form-control" rows="3">{{ old('specification') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Purpose</label>
            <textarea name="purpose" class="form-control" rows="3" required>{{ old('purpose') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
