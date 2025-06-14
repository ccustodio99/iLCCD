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
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
