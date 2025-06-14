@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="container">
    @include('components.breadcrumbs', ['links' => [
        ['label' => 'Settings']
    ]])
    <h1 class="mb-2">System Settings</h1>
    <p class="text-muted mb-4">Administrators manage system defaults and theme settings here.</p>
    <div class="mb-4">
        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#settingsModal">Quick Settings</a>
    </div>
    <div class="row row-cols-1 row-cols-md-2 g-3">
        <div class="col">
            <a href="{{ route('ticket-categories.index') }}" class="card card-quick text-center text-decoration-none p-4 h-100" aria-label="Ticket Categories" data-bs-toggle="modal" data-bs-target="#ticketCategoriesModal">
                <span class="material-symbols-outlined d-block mb-2" aria-hidden="true">category</span>
                <span class="fw-semibold">Ticket Categories</span>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('job-order-types.index') }}" class="card card-quick text-center text-decoration-none p-4 h-100" aria-label="Job Order Types" data-bs-toggle="modal" data-bs-target="#jobOrderTypesModal">
                <span class="material-symbols-outlined d-block mb-2" aria-hidden="true">work</span>
                <span class="fw-semibold">Job Order Types</span>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('inventory-categories.index') }}" class="card card-quick text-center text-decoration-none p-4 h-100" aria-label="Inventory Categories" data-bs-toggle="modal" data-bs-target="#inventoryCategoriesModal">
                <span class="material-symbols-outlined d-block mb-2" aria-hidden="true">inventory_2</span>
                <span class="fw-semibold">Inventory Categories</span>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('document-categories.index') }}" class="card card-quick text-center text-decoration-none p-4 h-100" aria-label="Document Categories" data-bs-toggle="modal" data-bs-target="#documentCategoriesModal">
                <span class="material-symbols-outlined d-block mb-2" aria-hidden="true">folder</span>
                <span class="fw-semibold">Document Categories</span>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('announcements.index') }}" class="card card-quick text-center text-decoration-none p-4 h-100" aria-label="Announcements" data-bs-toggle="modal" data-bs-target="#announcementsModal">
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
        <div class="col">
            <a href="{{ route('settings.branding') }}" class="card card-quick text-center text-decoration-none p-4 h-100" aria-label="Branding">
                <span class="material-symbols-outlined d-block mb-2" aria-hidden="true">image</span>
                <span class="fw-semibold">Branding</span>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('settings.institution') }}" class="card card-quick text-center text-decoration-none p-4 h-100" aria-label="Institution">
                <span class="material-symbols-outlined d-block mb-2" aria-hidden="true">school</span>
                <span class="fw-semibold">Institution</span>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('settings.localization') }}" class="card card-quick text-center text-decoration-none p-4 h-100" aria-label="Localization">
                <span class="material-symbols-outlined d-block mb-2" aria-hidden="true">schedule</span>
                <span class="fw-semibold">Localization</span>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('settings.notifications') }}" class="card card-quick text-center text-decoration-none p-4 h-100" aria-label="Notifications">
                <span class="material-symbols-outlined d-block mb-2" aria-hidden="true">notifications</span>
                <span class="fw-semibold">Notifications</span>
            </a>
        </div>
    </div>
</div>
@include('settings.ticket-categories.modal-index', ['categories' => $ticketCategories])
@include('settings.job-order-types.modal-index', ['types' => $jobOrderTypes])
@include('settings.inventory-categories.modal-index', ['categories' => $inventoryCategories])
@include('settings.document-categories.modal-index', ['categories' => $documentCategories])
@include('settings.announcements.modal-index', ['announcements' => $announcements])
@endsection
