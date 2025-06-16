<div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="settingsModalLabel" class="modal-title">System Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
