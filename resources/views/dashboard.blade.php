@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">Dashboard</h1>

    @if($announcements->count())
    <div class="mb-4">
        <h2>Announcements</h2>
        <ul class="list-group">
            @foreach($announcements as $announce)
                <li class="list-group-item">
                    <strong>{{ $announce->title }}</strong><br>
                    <span class="small text-muted">{{ Str::limit($announce->content, 100) }}</span>
                </li>
            @endforeach
        </ul>
    </div>
    @endif

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
    <table class="table table-striped" id="tickets-table">
        <thead>
            <tr>
                <th>Subject</th>
                <th>Status</th>
                <th>Due</th>
            </tr>
        </thead>
        <tbody id="tickets-body">
            @forelse ($tickets as $ticket)
            <tr>
                <td>{{ $ticket->formatted_subject }}</td>
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
    <table class="table table-striped" id="job-orders-table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Description</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="job-orders-body">
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
            @foreach([\App\Models\Requisition::STATUS_PENDING_HEAD => 'Pending Head', \App\Models\Requisition::STATUS_APPROVED => 'Approved'] as $val => $label)
                <option value="{{ $val }}" {{ request('requisition_status') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </form>
    <div class="table-responsive">
    <table class="table table-striped" id="requisitions-table">
        <thead>
            <tr>
                <th>Purpose</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="requisitions-body">
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
    <table class="table table-striped" id="purchase-orders-table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="purchase-orders-body">
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
            <table class="table table-striped" id="incoming-docs-table">
                <thead>
                    <tr>
                        <th>Document</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody id="incoming-docs-body">
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
            <table class="table table-striped" id="outgoing-docs-table">
                <thead>
                    <tr>
                        <th>Document</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody id="outgoing-docs-body">
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
    <table class="table table-striped" id="for-approval-table">
        <thead>
            <tr>
                <th>Document</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody id="for-approval-body">
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
