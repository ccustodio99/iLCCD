@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">My Inventory Items</h1>
    <a href="{{ route('inventory.create') }}" class="btn btn-primary mb-3">Add Item</a>
    <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ ucfirst($item->status) }}</td>
                <td>
                    <a href="{{ route('inventory.edit', $item) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('inventory.destroy', $item) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this item?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    {{ $items->links() }}
</div>
@endsection
