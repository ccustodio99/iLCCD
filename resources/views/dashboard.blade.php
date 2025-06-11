@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">Dashboard</h1>

    <h2 class="mt-4">Pending Tickets</h2>
    <form method="GET" class="mb-2">
        <select name="ticket_status" class="form-select w-auto d-inline" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            @foreach(['open' => 'Open', 'escalated' => 'Escalated', 'converted' => 'Converted'] as $val => $label)
                <option value="{{ $val }}" {{ request('ticket_status') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </form>
    <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Subject</th>
                <th>Status</th>
                <th>Due</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tickets as $ticket)
            <tr>
                <td>{{ $ticket->subject }}</td>
                <td>{{ ucfirst($ticket->status) }}</td>
                <td>{{ optional($ticket->due_at)->format('Y-m-d') }}</td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center">No records</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    {{ $tickets->appends(request()->except('tickets_page'))->links() }}

    <h2 class="mt-5">Pending Job Orders</h2>
    <form method="GET" class="mb-2">
        <select name="job_status" class="form-select w-auto d-inline" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            @foreach(['new' => 'New', 'approved' => 'Approved', 'assigned' => 'Assigned', 'in_progress' => 'In Progress'] as $val => $label)
                <option value="{{ $val }}" {{ request('job_status') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </form>
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
            @forelse ($jobOrders as $order)
            <tr>
                <td>{{ $order->job_type }}</td>
                <td>{{ Str::limit($order->description, 50) }}</td>
                <td>{{ ucfirst($order->status) }}</td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center">No records</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    {{ $jobOrders->appends(request()->except('job_orders_page'))->links() }}

    <h2 class="mt-5">Pending Requisitions</h2>
    <form method="GET" class="mb-2">
        <select name="requisition_status" class="form-select w-auto d-inline" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            @foreach(['pending_head' => 'Pending Head', 'approved' => 'Approved'] as $val => $label)
                <option value="{{ $val }}" {{ request('requisition_status') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </form>
    <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Purpose</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($requisitions as $req)
            <tr>
                <td>{{ Str::limit($req->purpose, 50) }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $req->status)) }}</td>
            </tr>
            @empty
            <tr><td colspan="2" class="text-center">No records</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    {{ $requisitions->appends(request()->except('requisitions_page'))->links() }}

    <h2 class="mt-5">Pending Purchase Orders</h2>
    <form method="GET" class="mb-2">
        <select name="po_status" class="form-select w-auto d-inline" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            @foreach(['draft' => 'Draft', 'ordered' => 'Ordered'] as $val => $label)
                <option value="{{ $val }}" {{ request('po_status') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </form>
    <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($purchaseOrders as $po)
            <tr>
                <td>{{ $po->item }}</td>
                <td>{{ $po->quantity }}</td>
                <td>{{ ucfirst($po->status) }}</td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center">No records</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    {{ $purchaseOrders->appends(request()->except('purchase_orders_page'))->links() }}

    <h2 class="mt-5">Incoming &amp; Outgoing Documents</h2>
    <div class="row">
        <div class="col-md-6">
            <h5>Incoming</h5>
            <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Document</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($incomingDocuments as $log)
                    <tr>
                        <td>{{ $log->document->title }}</td>
                        <td>{{ $log->user->name }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-center">No records</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
            {{ $incomingDocuments->appends(request()->except('incoming_docs_page'))->links() }}
        </div>
        <div class="col-md-6">
            <h5>Outgoing</h5>
            <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Document</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($outgoingDocuments as $log)
                    <tr>
                        <td>{{ $log->document->title }}</td>
                        <td>{{ $log->user->name }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-center">No records</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
            {{ $outgoingDocuments->appends(request()->except('outgoing_docs_page'))->links() }}
        </div>
    </div>

    <h2 class="mt-5">For Approval/Checking</h2>
    <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Document</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($forApprovalDocuments as $log)
            <tr>
                <td>{{ $log->document->title }}</td>
                <td>{{ $log->user->name }}</td>
            </tr>
            @empty
            <tr><td colspan="2" class="text-center">No records</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    {{ $forApprovalDocuments->appends(request()->except('for_approval_docs_page'))->links() }}
</div>
@endsection
