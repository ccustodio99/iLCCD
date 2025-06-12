@extends('layouts.app')

@section('title', 'My Assigned Job Orders')

@section('content')
<div class="container">
    <h1 class="mb-4">My Assigned Job Orders</h1>
    <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Type</th>
                <th>Description</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jobOrders as $order)
            <tr>
                <td>{{ $order->job_type }}</td>
                <td>{{ Str::limit($order->description, 50) }}</td>
                <td>{{ ucfirst(str_replace('_',' ', $order->status)) }}</td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center">No job orders</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    {{ $jobOrders->links() }}
</div>
@endsection
