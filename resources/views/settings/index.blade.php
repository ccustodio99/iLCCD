@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="container">
    @include('components.breadcrumbs', ['links' => [
        ['label' => 'Settings']
    ]])
    <h1 class="mb-2">System Settings</h1>
    <p class="text-muted mb-4">Administrators manage system defaults and theme settings here.</p>

    <div class="accordion" id="settingsAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingCategories">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCategories" aria-expanded="true" aria-controls="collapseCategories">
                    Categories
                </button>
            </h2>
            <div id="collapseCategories" class="accordion-collapse collapse show" aria-labelledby="headingCategories" data-bs-parent="#settingsAccordion">
                <div class="accordion-body">
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
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingGeneral">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGeneral" aria-expanded="false" aria-controls="collapseGeneral">
                    General
                </button>
            </h2>
            <div id="collapseGeneral" class="accordion-collapse collapse" aria-labelledby="headingGeneral" data-bs-parent="#settingsAccordion">
                <div class="accordion-body">
                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        <div class="col">
                            <x-settings-link :href="route('settings.theme')" icon="color_lens" label="Appearance" />
                        </div>
                        <div class="col">
                            <x-settings-link :href="route('settings.localization')" icon="schedule" label="Localization" />
                        </div>
                        <div class="col">
                            <x-settings-link :href="route('settings.sla')" icon="priority_high" label="Ticket Escalation" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingWorkflow">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWorkflow" aria-expanded="false" aria-controls="collapseWorkflow">
                    Workflow
                </button>
            </h2>
            <div id="collapseWorkflow" class="accordion-collapse collapse" aria-labelledby="headingWorkflow" data-bs-parent="#settingsAccordion">
                <div class="accordion-body">
                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        <div class="col">
                            <x-settings-link :href="route('approval-processes.index')" icon="account_tree" label="Approval Processes" />
                        </div>
                        <div class="col">
                            <x-settings-link :href="route('settings.sla')" icon="priority_high" label="Ticket Escalation" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingCommunication">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCommunication" aria-expanded="false" aria-controls="collapseCommunication">
                    Communication
                </button>
            </h2>
            <div id="collapseCommunication" class="accordion-collapse collapse" aria-labelledby="headingCommunication" data-bs-parent="#settingsAccordion">
                <div class="accordion-body">
                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        <div class="col">
                            <x-settings-link :href="route('announcements.index')" icon="campaign" label="Announcements" />
                        </div>
                        <div class="col">
                            <x-settings-link :href="route('settings.notifications')" icon="notifications" label="Notifications" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
