@extends('layouts.app')

@section('title', 'Notification Settings')

@section('content')
<div class="container">
    @include('components.breadcrumbs', ['links' => [
        ['label' => 'Settings', 'url' => route('settings.index')],
        ['label' => 'Notifications']
    ]])
    <h1 class="mb-4">Notification Settings</h1>
    <form action="{{ route('settings.notifications.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" role="switch" id="notify_ticket_updates" name="notify_ticket_updates" value="1" {{ setting('notify_ticket_updates', true) ? 'checked' : '' }}>
            <label class="form-check-label" for="notify_ticket_updates">Email Ticket Updates</label>
        </div>
        <div class="mb-3">
            <label for="template_ticket_updates" class="form-label">Ticket Update Template</label>
            <textarea id="template_ticket_updates" name="template_ticket_updates" rows="6" class="form-control">{{ setting('template_ticket_updates', '{{ '{{ message }}' }}') }}</textarea>
        </div>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" role="switch" id="notify_job_order_status" name="notify_job_order_status" value="1" {{ setting('notify_job_order_status', true) ? 'checked' : '' }}>
            <label class="form-check-label" for="notify_job_order_status">Email Job Order Status</label>
        </div>
        <div class="mb-3">
            <label for="template_job_order_status" class="form-label">Job Order Template</label>
            <textarea id="template_job_order_status" name="template_job_order_status" rows="6" class="form-control">{{ setting('template_job_order_status', '{{ '{{ message }}' }}') }}</textarea>
        </div>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" role="switch" id="notify_requisition_status" name="notify_requisition_status" value="1" {{ setting('notify_requisition_status', true) ? 'checked' : '' }}>
            <label class="form-check-label" for="notify_requisition_status">Email Requisition Status</label>
        </div>
        <div class="mb-3">
            <label for="template_requisition_status" class="form-label">Requisition Template</label>
            <textarea id="template_requisition_status" name="template_requisition_status" rows="6" class="form-control">{{ setting('template_requisition_status', '{{ '{{ message }}' }}') }}</textarea>
        </div>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" role="switch" id="notify_low_stock" name="notify_low_stock" value="1" {{ setting('notify_low_stock', true) ? 'checked' : '' }}>
            <label class="form-check-label" for="notify_low_stock">Email Low Stock Alerts</label>
        </div>
        <div class="mb-3">
            <label for="template_low_stock" class="form-label">Low Stock Template</label>
            <textarea id="template_low_stock" name="template_low_stock" rows="6" class="form-control">{{ setting('template_low_stock', '{{ '{{ message }}' }}') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="{{ route('settings.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="{{ asset('vendor/easymde/easymde.min.css') }}" />
<script src="{{ asset('vendor/easymde/easymde.min.js') }}"></script>
<script>
    new EasyMDE({ element: document.getElementById('template_ticket_updates') });
    new EasyMDE({ element: document.getElementById('template_job_order_status') });
    new EasyMDE({ element: document.getElementById('template_requisition_status') });
    new EasyMDE({ element: document.getElementById('template_low_stock') });
</script>
@endpush
