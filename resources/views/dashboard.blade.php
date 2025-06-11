@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">Dashboard</h1>
    <p class="text-center">You are logged in!</p>
    <div class="row g-4 justify-content-center">
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('tickets.index') }}" class="text-decoration-none">
                <div class="card card-quick text-center p-3 h-100">
                    <span class="material-symbols-outlined">confirmation_number</span>
                    <h5 class="mt-2">Tickets</h5>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('job-orders.index') }}" class="text-decoration-none">
                <div class="card card-quick text-center p-3 h-100">
                    <span class="material-symbols-outlined">work</span>
                    <h5 class="mt-2">Job Orders</h5>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('requisitions.index') }}" class="text-decoration-none">
                <div class="card card-quick text-center p-3 h-100">
                    <span class="material-symbols-outlined">request_quote</span>
                    <h5 class="mt-2">Requisitions</h5>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('inventory.index') }}" class="text-decoration-none">
                <div class="card card-quick text-center p-3 h-100">
                    <span class="material-symbols-outlined">inventory_2</span>
                    <h5 class="mt-2">Inventory</h5>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('purchase-orders.index') }}" class="text-decoration-none">
                <div class="card card-quick text-center p-3 h-100">
                    <span class="material-symbols-outlined">shopping_cart</span>
                    <h5 class="mt-2">Purchase Orders</h5>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('documents.index') }}" class="text-decoration-none">
                <div class="card card-quick text-center p-3 h-100">
                    <span class="material-symbols-outlined">description</span>
                    <h5 class="mt-2">Documents</h5>
                </div>
            </a>
        </div>
    </div>
    <h2 class="mt-5 text-center">Document Tracking</h2>
    <div class="row g-4 justify-content-center">
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('document-tracking.incoming') }}" class="text-decoration-none">
                <div class="card card-quick text-center p-3 h-100">
                    <span class="material-symbols-outlined">inbox</span>
                    <h5 class="mt-2">Incoming</h5>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('document-tracking.outgoing') }}" class="text-decoration-none">
                <div class="card card-quick text-center p-3 h-100">
                    <span class="material-symbols-outlined">outbox</span>
                    <h5 class="mt-2">Outgoing</h5>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('document-tracking.for-approval') }}" class="text-decoration-none">
                <div class="card card-quick text-center p-3 h-100">
                    <span class="material-symbols-outlined">assignment_turned_in</span>
                    <h5 class="mt-2">For Approval/Checking</h5>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('document-tracking.tracking') }}" class="text-decoration-none">
                <div class="card card-quick text-center p-3 h-100">
                    <span class="material-symbols-outlined">track_changes</span>
                    <h5 class="mt-2">Tracking</h5>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('document-tracking.reports') }}" class="text-decoration-none">
                <div class="card card-quick text-center p-3 h-100">
                    <span class="material-symbols-outlined">insights</span>
                    <h5 class="mt-2">Other Reports</h5>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
