@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="container">
    @include('components.breadcrumbs', ['links' => [
        ['label' => 'Settings']
    ]])
    <h1 class="mb-2">System Settings</h1>
    <p class="text-muted mb-4">Administrators manage system defaults and theme settings here.</p>
    <div class="row row-cols-1 row-cols-md-2 g-3">
        <div class="col">
            <x-settings-link :href="route('ticket-categories.index')" icon="category" label="Ticket Categories" />
        </div>
        <div class="col">
            <x-settings-link :href="route('job-order-types.index')" icon="work" label="Job Order Types" />
        </div>
        <div class="col">
            <x-settings-link :href="route('inventory-categories.index')" icon="inventory_2" label="Inventory Categories" />
        </div>
        <div class="col">
            <x-settings-link :href="route('document-categories.index')" icon="folder" label="Document Categories" />
        </div>
        <div class="col">
            <x-settings-link :href="route('announcements.index')" icon="campaign" label="Announcements" />
        </div>
        <div class="col">
            <x-settings-link :href="route('settings.theme')" icon="color_lens" label="Theme" />
        </div>
        <div class="col">
            <x-settings-link :href="route('settings.branding')" icon="image" label="Branding" />
        </div>
        <div class="col">
            <x-settings-link :href="route('settings.institution')" icon="school" label="Institution" />
        </div>
        <div class="col">
            <x-settings-link :href="route('settings.localization')" icon="schedule" label="Localization" />
        </div>
        <div class="col">
            <x-settings-link :href="route('settings.notifications')" icon="notifications" label="Notifications" />
        </div>
    </div>
</div>
@endsection
