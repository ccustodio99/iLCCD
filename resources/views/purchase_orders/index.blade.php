@extends('layouts.app')

@section('title', 'Purchase Orders')

@section('content')
<div class="container">
    <h1 class="mb-4">Purchase Orders</h1>
    @include('components.per-page-selector')
    <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary mb-3">New Purchase Order</a>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">Purchase Orders</caption>
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Supplier</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td>{{ $order->item }}</td>
                <td>{{ $order->quantity }}</td>
                <td>{{ $order->supplier }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $order->status)) }}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#purchaseOrderModal{{ $order->id }}">Details</button>
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

    @foreach ($orders as $order)
    <div class="modal fade" id="purchaseOrderModal{{ $order->id }}" tabindex="-1" aria-labelledby="purchaseOrderModalLabel{{ $order->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="purchaseOrderModalLabel{{ $order->id }}">Purchase Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Item:</strong> {{ $order->item }}</p>
                    <p><strong>Quantity:</strong> {{ $order->quantity }}</p>
                    <p><strong>Supplier:</strong> {{ $order->supplier }}</p>
                    <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
                    <p><strong>Requisition ID:</strong> {{ $order->requisition_id }}</p>
                    <p><strong>Inventory Item ID:</strong> {{ $order->inventory_item_id }}</p>
                    <p><strong>Ordered At:</strong> {{ optional($order->ordered_at)->format('Y-m-d H:i') }}</p>
                    <p><strong>Received At:</strong> {{ optional($order->received_at)->format('Y-m-d H:i') }}</p>
                    @if($order->attachment_path)
                        <p><a href="{{ route('purchase-orders.attachment', $order) }}" target="_blank">Download Attachment</a></p>
                    @endif
                    @include('audit_trails._list', ['logs' => $order->auditTrails])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
