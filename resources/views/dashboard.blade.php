@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h1 class="mb-4">Dashboard</h1>
    <p>You are logged in!</p>
    <div class="row justify-content-center">
        <div class="col-6 col-md-4 mb-3">
            <a href="{{ route('tickets.index') }}" class="btn cta w-100">Tickets</a>
        </div>
        <div class="col-6 col-md-4 mb-3">
            <a href="{{ route('job-orders.index') }}" class="btn cta w-100">Job Orders</a>
        </div>
        <div class="col-6 col-md-4 mb-3">
            <a href="{{ route('requisitions.index') }}" class="btn cta w-100">Requisitions</a>
        </div>
        <div class="col-6 col-md-4 mb-3">
            <a href="{{ route('inventory.index') }}" class="btn cta w-100">Inventory</a>
        </div>
        <div class="col-6 col-md-4 mb-3">
            <a href="{{ route('purchase-orders.index') }}" class="btn cta w-100">Purchase Orders</a>
        </div>
        <div class="col-6 col-md-4 mb-3">
            <a href="{{ route('documents.index') }}" class="btn cta w-100">Documents</a>
        </div>
    </div>
    <h2 class="mt-4">Document Tracking</h2>
    <div class="row justify-content-center">
        <div class="col-6 col-md-4 mb-3">
            <a href="{{ route('document-tracking.incoming') }}" class="btn cta w-100">Incoming</a>
        </div>
        <div class="col-6 col-md-4 mb-3">
            <a href="{{ route('document-tracking.outgoing') }}" class="btn cta w-100">Outgoing</a>
        </div>
        <div class="col-6 col-md-4 mb-3">
            <a href="{{ route('document-tracking.for-approval') }}" class="btn cta w-100">For Approval/Checking</a>
        </div>
        <div class="col-6 col-md-4 mb-3">
            <a href="{{ route('document-tracking.tracking') }}" class="btn cta w-100">Tracking</a>
        </div>
        <div class="col-6 col-md-4 mb-3">
            <a href="{{ route('document-tracking.reports') }}" class="btn cta w-100">Other Reports</a>
        </div>
    </div>
</div>
@endsection
