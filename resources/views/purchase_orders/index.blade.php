@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Purchase Orders</h1>
    <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary mb-3">New Purchase Order</a>
    <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td>{{ $order->item }}</td>
                <td>{{ $order->quantity }}</td>
                <td>{{ ucfirst($order->status) }}</td>
                <td>
                    <a href="{{ route('purchase-orders.edit', $order) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('purchase-orders.destroy', $order) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this purchase order?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    {{ $orders->links() }}
</div>
@endsection
