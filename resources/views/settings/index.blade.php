@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="container">
    <h1 class="mb-4">System Settings</h1>
    <div class="row row-cols-1 row-cols-md-2 g-3">
        <div class="col">
            <a href="{{ route('ticket-categories.index') }}" class="card card-quick text-center text-decoration-none p-4 h-100" aria-label="Ticket Categories">
                <span class="material-symbols-outlined d-block mb-2" aria-hidden="true">category</span>
                <span class="fw-semibold">Ticket Categories</span>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('job-order-types.index') }}" class="card card-quick text-center text-decoration-none p-4 h-100" aria-label="Job Order Types">
                <span class="material-symbols-outlined d-block mb-2" aria-hidden="true">work</span>
                <span class="fw-semibold">Job Order Types</span>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('inventory-categories.index') }}" class="card card-quick text-center text-decoration-none p-4 h-100" aria-label="Inventory Categories">
                <span class="material-symbols-outlined d-block mb-2" aria-hidden="true">inventory_2</span>
                <span class="fw-semibold">Inventory Categories</span>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('document-categories.index') }}" class="card card-quick text-center text-decoration-none p-4 h-100" aria-label="Document Categories">
                <span class="material-symbols-outlined d-block mb-2" aria-hidden="true">folder</span>
                <span class="fw-semibold">Document Categories</span>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('announcements.index') }}" class="card card-quick text-center text-decoration-none p-4 h-100" aria-label="Announcements">
                <span class="material-symbols-outlined d-block mb-2" aria-hidden="true">campaign</span>
                <span class="fw-semibold">Announcements</span>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('settings.theme') }}" class="card card-quick text-center text-decoration-none p-4 h-100" aria-label="Theme">
                <span class="material-symbols-outlined d-block mb-2" aria-hidden="true">color_lens</span>
                <span class="fw-semibold">Theme</span>
            </a>
        </div>
    </div>
</div>
@endsection
