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
                        <a href="<?php echo e(route('ticket-categories.index')); ?>" class="card card-quick text-center text-decoration-none p-4 h-100" aria-label="Ticket Categories">
                            <span class="material-symbols-outlined d-block mb-2" aria-hidden="true">category</span>
                            <span class="fw-semibold">Ticket Categories</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="<?php echo e(route('job-order-types.index')); ?>" class="card card-quick text-center text-decoration-none p-4 h-100" aria-label="Job Order Types">
                            <span class="material-symbols-outlined d-block mb-2" aria-hidden="true">work</span>
                            <span class="fw-semibold">Job Order Types</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="<?php echo e(route('inventory-categories.index')); ?>" class="card card-quick text-center text-decoration-none p-4 h-100" aria-label="Inventory Categories">
                            <span class="material-symbols-outlined d-block mb-2" aria-hidden="true">inventory_2</span>
                            <span class="fw-semibold">Inventory Categories</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="<?php echo e(route('document-categories.index')); ?>" class="card card-quick text-center text-decoration-none p-4 h-100" aria-label="Document Categories">
                            <span class="material-symbols-outlined d-block mb-2" aria-hidden="true">folder</span>
                            <span class="fw-semibold">Document Categories</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="<?php echo e(route('announcements.index')); ?>" class="card card-quick text-center text-decoration-none p-4 h-100" aria-label="Announcements">
                            <span class="material-symbols-outlined d-block mb-2" aria-hidden="true">campaign</span>
                            <span class="fw-semibold">Announcements</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="<?php echo e(route('settings.theme')); ?>" class="card card-quick text-center text-decoration-none p-4 h-100" aria-label="Theme">
                            <span class="material-symbols-outlined d-block mb-2" aria-hidden="true">color_lens</span>
                            <span class="fw-semibold">Theme</span>
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
<?php /**PATH D:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/settings/partials/settings-modal.blade.php ENDPATH**/ ?>