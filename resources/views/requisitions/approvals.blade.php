@extends('layouts.app')

@section('title', 'Requisition Approvals')

@section('content')
<div class="container">
    <h1 class="mb-4">Requisitions for Approval</h1>
    <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Items</th>
                <th>Purpose</th>
                <th>Requester</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requisitions as $req)
            <tr>
                <td>
                    <ul class="mb-0">
                        @foreach($req->items as $item)
                        <li>{{ $item->item }} ({{ $item->quantity }})</li>
                        @endforeach
                    </ul>
                </td>
                <td>{{ Str::limit($req->purpose, 50) }}</td>
                <td>{{ $req->user->name }}</td>
                <td>
                    <form action="{{ route('requisitions.approve', $req) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm btn-primary">Approve</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">No requisitions</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    {{ $requisitions->links() }}
</div>
@endsection
