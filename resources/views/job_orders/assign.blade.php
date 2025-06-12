@extends('layouts.app')

@section('title', 'Assign Job Orders')

@section('content')
<div class="container">
    <h1 class="mb-4">Approved Job Orders</h1>
    @include('components.per-page-selector')
    <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Type</th>
                <th>Description</th>
                <th>Assign To</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jobOrders as $order)
            <tr>
                <td>{{ $order->job_type }}</td>
                <td>{{ Str::limit($order->description, 50) }}</td>
                <td>
                    <form action="{{ route('job-orders.assign', $order) }}" method="POST" class="d-flex">
                        @csrf
                        @method('PUT')
                        <select name="assigned_to_id" class="form-select form-select-sm me-2" required>
                            @foreach($staff as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Assign</button>
                    </form>
                </td>
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
