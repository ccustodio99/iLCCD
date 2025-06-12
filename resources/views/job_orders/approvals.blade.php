@extends('layouts.app')

@section('title', 'Job Order Approvals')

@section('content')
<div class="container">
    <h1 class="mb-4">Job Orders for Approval</h1>
    <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Type</th>
                <th>Description</th>
                <th>Requester</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jobOrders as $order)
            <tr>
                <td>{{ $order->job_type }}</td>
                <td>{{ Str::limit($order->description, 50) }}</td>
                <td>{{ $order->user->name }}</td>
                <td>
                    <form action="{{ route('job-orders.approve', $order) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm btn-primary">Approve</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">No job orders</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    {{ $jobOrders->links() }}
</div>
@endsection
