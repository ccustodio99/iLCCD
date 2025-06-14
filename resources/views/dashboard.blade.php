@extends('layouts.app', ['showSidebar' => false])

@php use Illuminate\Support\Str; @endphp

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">Dashboard</h1>

    <div class="row row-cols-2 row-cols-md-5 g-3 mb-3 text-center" id="dashboard-summary">
        <div class="col">
            <div class="card card-quick p-3" aria-label="Pending Tickets">
                <span class="material-symbols-outlined d-block" aria-hidden="true">confirmation_number</span>
                <span class="fw-semibold">Tickets</span>
                <span class="display-6" id="count-tickets">{{ $tickets->total() }}</span>
            </div>
        </div>
        <div class="col">
            <div class="card card-quick p-3" aria-label="Pending Job Orders">
                <span class="material-symbols-outlined d-block" aria-hidden="true">engineering</span>
                <span class="fw-semibold">Job Orders</span>
                <span class="display-6" id="count-jobs">{{ $jobOrders->total() }}</span>
            </div>
        </div>
        <div class="col">
            <div class="card card-quick p-3" aria-label="Pending Requisitions">
                <span class="material-symbols-outlined d-block" aria-hidden="true">receipt</span>
                <span class="fw-semibold">Requisitions</span>
                <span class="display-6" id="count-requisitions">{{ $requisitions->total() }}</span>
            </div>
        </div>
        <div class="col">
            <div class="card card-quick p-3" aria-label="Pending Purchase Orders">
                <span class="material-symbols-outlined d-block" aria-hidden="true">shopping_cart</span>
                <span class="fw-semibold">Purchase Orders</span>
                <span class="display-6" id="count-pos">{{ $purchaseOrders->total() }}</span>
            </div>
        </div>
        <div class="col">
            <div class="card card-quick p-3" aria-label="Recent Document Logs">
                <span class="material-symbols-outlined d-block" aria-hidden="true">description</span>
                <span class="fw-semibold">Documents</span>
                <span class="display-6" id="count-docs">{{ $incomingDocuments->total() + $outgoingDocuments->total() + $forApprovalDocuments->total() }}</span>
            </div>
        </div>
    </div>

    <nav class="visually-hidden-focusable mb-4" aria-label="Dashboard sections">
        <a href="#tickets-section" class="me-3">Skip to Tickets</a>
        <a href="#job-orders-section" class="me-3">Skip to Job Orders</a>
        <a href="#requisitions-section" class="me-3">Skip to Requisitions</a>
        <a href="#purchase-orders-section" class="me-3">Skip to Purchase Orders</a>
        <a href="#documents-section">Skip to Documents</a>
    </nav>

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

    <h2 id="tickets-section" class="mt-4">Pending Tickets</h2>
    <form method="GET" class="mb-2">
        @foreach(request()->except(['ticket_status','tickets_page','job_orders_page','requisitions_page','purchase_orders_page','incoming_docs_page','outgoing_docs_page','for_approval_docs_page']) as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endforeach
        <select name="ticket_status" class="form-select w-auto d-inline" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            @foreach(['open' => 'Open', 'escalated' => 'Escalated', 'converted' => 'Converted'] as $val => $label)
                <option value="{{ $val }}" {{ request('ticket_status') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </form>
    <div class="table-responsive">
    <table class="table table-striped" id="tickets-table">
    <caption class="visually-hidden">Pending Tickets</caption>
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
    {{ $tickets->appends(request()->except(['tickets_page','job_orders_page','requisitions_page','purchase_orders_page','incoming_docs_page','outgoing_docs_page','for_approval_docs_page']))->links() }}

    <h2 id="job-orders-section" class="mt-5">Pending Job Orders</h2>
    <form method="GET" class="mb-2">
        @foreach(request()->except(['job_status','tickets_page','job_orders_page','requisitions_page','purchase_orders_page','incoming_docs_page','outgoing_docs_page','for_approval_docs_page']) as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endforeach
        <select name="job_status" class="form-select w-auto d-inline" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            @foreach([
                'pending_head' => 'Pending Head',
                'pending_president' => 'Pending President',
                'pending_finance' => 'Pending Finance',
                'approved' => 'Approved',
                'assigned' => 'Assigned',
                'in_progress' => 'In Progress',
                'completed' => 'Completed',
                'closed' => 'Closed'
            ] as $val => $label)
                <option value="{{ $val }}" {{ request('job_status') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </form>
    <div class="table-responsive">
    <table class="table table-striped" id="job-orders-table">
    <caption class="visually-hidden">Pending Job Orders</caption>
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
    {{ $jobOrders->appends(request()->except(['tickets_page','job_orders_page','requisitions_page','purchase_orders_page','incoming_docs_page','outgoing_docs_page','for_approval_docs_page']))->links() }}

    <h2 id="requisitions-section" class="mt-5">Pending Requisitions</h2>
    <form method="GET" class="mb-2">
        @foreach(request()->except(['requisition_status','tickets_page','job_orders_page','requisitions_page','purchase_orders_page','incoming_docs_page','outgoing_docs_page','for_approval_docs_page']) as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endforeach
        <select name="requisition_status" class="form-select w-auto d-inline" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            @foreach([\App\Models\Requisition::STATUS_PENDING_HEAD => 'Pending Head', \App\Models\Requisition::STATUS_APPROVED => 'Approved'] as $val => $label)
                <option value="{{ $val }}" {{ request('requisition_status') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </form>
    <div class="table-responsive">
    <table class="table table-striped" id="requisitions-table">
    <caption class="visually-hidden">Pending Requisitions</caption>
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
    {{ $requisitions->appends(request()->except(['tickets_page','job_orders_page','requisitions_page','purchase_orders_page','incoming_docs_page','outgoing_docs_page','for_approval_docs_page']))->links() }}

    <h2 id="purchase-orders-section" class="mt-5">Pending Purchase Orders</h2>
    <form method="GET" class="mb-2">
        @foreach(request()->except(['po_status','tickets_page','job_orders_page','requisitions_page','purchase_orders_page','incoming_docs_page','outgoing_docs_page','for_approval_docs_page']) as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endforeach
        <select name="po_status" class="form-select w-auto d-inline" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            @foreach(\App\Models\PurchaseOrder::STATUSES as $status)
                <option value="{{ $status }}" {{ request('po_status') === $status ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                </option>
            @endforeach
        </select>
    </form>
    <div class="table-responsive">
    <table class="table table-striped" id="purchase-orders-table">
    <caption class="visually-hidden">Pending Purchase Orders</caption>
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
                <td>{{ ucfirst(str_replace('_', ' ', $po->status)) }}</td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center">No records</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    {{ $purchaseOrders->appends(request()->except(['tickets_page','job_orders_page','requisitions_page','purchase_orders_page','incoming_docs_page','outgoing_docs_page','for_approval_docs_page']))->links() }}

    <h2 id="documents-section" class="mt-5">Incoming &amp; Outgoing Documents</h2>
    <div class="row">
        <div class="col-md-6">
            <h5>Incoming</h5>
            <div class="table-responsive">
            <table class="table table-striped" id="incoming-docs-table">
                <caption class="visually-hidden">Incoming Documents</caption>
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
            {{ $incomingDocuments->appends(request()->except(['tickets_page','job_orders_page','requisitions_page','purchase_orders_page','incoming_docs_page','outgoing_docs_page','for_approval_docs_page']))->links() }}
        </div>
        <div class="col-md-6">
            <h5>Outgoing</h5>
            <div class="table-responsive">
            <table class="table table-striped" id="outgoing-docs-table">
                <caption class="visually-hidden">Outgoing Documents</caption>
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
            {{ $outgoingDocuments->appends(request()->except(['tickets_page','job_orders_page','requisitions_page','purchase_orders_page','incoming_docs_page','outgoing_docs_page','for_approval_docs_page']))->links() }}
        </div>
    </div>

    <h2 id="for-approval-section" class="mt-5">For Approval/Checking</h2>
    <caption class="visually-hidden">Documents for Approval</caption>
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
    {{ $forApprovalDocuments->appends(request()->except(['tickets_page','job_orders_page','requisitions_page','purchase_orders_page','incoming_docs_page','outgoing_docs_page','for_approval_docs_page']))->links() }}
    <div id="dashboard-status" class="visually-hidden" aria-live="polite"></div>
</div>
@vite('resources/js/dashboard.js')
@endsection
