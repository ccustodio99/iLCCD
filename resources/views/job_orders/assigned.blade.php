@extends('layouts.app')

@section('title', 'My Assigned Job Orders')

@section('content')
<div class="container">
    <h1 class="mb-4">My Assigned Job Orders</h1>
    @include('components.per-page-selector')
    <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Type</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jobOrders as $order)
            <tr>
                <td>{{ $order->job_type }}</td>
                <td>{{ Str::limit($order->description, 50) }}</td>
                <td>{{ ucfirst(str_replace('_',' ', $order->status)) }}</td>
                <td>
                    @if(!$order->started_at)
                        <form action="{{ route('job-orders.start', $order) }}" method="POST" class="d-flex">
                            @csrf
                            @method('PUT')
                            <input type="text" name="notes" class="form-control form-control-sm me-2" placeholder="Notes">
                            <button type="submit" class="btn btn-sm btn-primary">Start</button>
                        </form>
                    @elseif(!$order->completed_at)
                        <form action="{{ route('job-orders.finish', $order) }}" method="POST" class="d-flex">
                            @csrf
                            @method('PUT')
                            <input type="text" name="notes" class="form-control form-control-sm me-2" placeholder="Feedback">
                            <button type="submit" class="btn btn-sm btn-success">Finish</button>
                        </form>
                    @else
                        <span class="text-muted">Done</span>
                    @endif
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
