<?php $__env->startSection('title', 'Tickets'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">My Tickets</h1>
    <?php echo $__env->make('components.per-page-selector', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="mb-3">
        <?php
            $filterCat = request('ticket_category_id');
        ?>
        <form method="GET" class="row row-cols-lg-auto g-2 align-items-end ticket-form">
            <div class="col">
                <label for="filter-status" class="form-label">Status</label>
                <select id="filter-status" name="status" class="form-select">
                    <option value="">Any</option>
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($status); ?>" <?php if(request('status') === $status): echo 'selected'; endif; ?>><?php echo e(ucfirst($status)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col">
                <label class="form-label">Category</label>
                <select id="filter-category" name="ticket_category_id" class="form-select">
                    <option value="">Any</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat->id); ?>" <?php echo e((string)$filterCat === (string)$cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col">
                <label for="filter-assigned" class="form-label">Assigned To</label>
                <select id="filter-assigned" name="assigned_to_id" class="form-select">
                    <option value="">Any</option>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($u->id); ?>" <?php if((string)request('assigned_to_id') === (string)$u->id): echo 'selected'; endif; ?>><?php echo e($u->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col">
                <label for="filter-search" class="form-label">Search</label>
                <input id="filter-search" type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="Subject or description">
            </div>
            <div class="col form-check mt-4">
                <input class="form-check-input" type="checkbox" value="1" id="filter-archived" name="archived" <?php echo e(request('archived') ? 'checked' : ''); ?>>
                <label class="form-check-label" for="filter-archived">Include Archived</label>
            </div>
            <div class="col">
                <button type="submit" class="btn btn-secondary">Filter</button>
            </div>
        </form>
    </div>
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#newTicketModal">New Ticket</button>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">Tickets</caption>
        <thead>
            <tr>
                <th>Category</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Due</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($ticket->ticketCategory->name); ?></td>
                <td><?php echo e($ticket->formatted_subject); ?></td>
                <td><?php echo e(ucfirst($ticket->status)); ?></td>
                <td><?php echo e(optional($ticket->due_at)->format('Y-m-d')); ?></td>
                <td>
                    <button type="button" class="btn btn-sm btn-info" data-details-url="<?php echo e(route('tickets.modal-details', $ticket)); ?>">Details</button>
                    <button type="button" class="btn btn-sm btn-primary ms-1" data-edit-url="<?php echo e(route('tickets.modal-edit', $ticket)); ?>">Edit</button>
                    <?php if($ticket->jobOrder): ?>
                        <span class="visually-hidden">Job Order ID <?php echo e($ticket->jobOrder->id); ?></span>
                    <?php endif; ?>
                    <?php if($ticket->requisitions->count()): ?>
                        <span class="visually-hidden">Requisitions <?php echo e($ticket->requisitions->pluck('id')->implode(' ')); ?></span>
                    <?php endif; ?>
                    <form action="<?php echo e(route('tickets.destroy', $ticket)); ?>" method="POST" class="d-inline ms-1">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Archive this ticket?')">Archive</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    </div>

    <?php echo e($tickets->links()); ?>


    <div class="modal fade" id="newTicketModal" tabindex="-1" aria-labelledby="newTicketModalLabel" aria-hidden="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="newTicketModalLabel" class="modal-title">New Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo e(route('tickets.store')); ?>" method="POST" enctype="multipart/form-data" class="ticket-form">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <?php
                            $selectedModalSub = old('ticket_category_id');
                            $selectedModalCat = null;
                            foreach ($categories as $cat) {
                                if ($cat->children->contains('id', $selectedModalSub)) {
                                    $selectedModalCat = $cat->id;
                                    break;
                                }
                            }
                            $categoryData = $categories->mapWithKeys(function($cat) {
                                return [$cat->id => $cat->children->map(fn($c) => ['id' => $c->id, 'name' => $c->name])];
                            });
                        ?>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select category-select mb-2" data-categories='<?php echo json_encode($categoryData, 15, 512) ?>' required>
                                <option value="">Select Category</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat->id); ?>" <?php echo e((string)$selectedModalCat === (string)$cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <select name="ticket_category_id" class="form-select subcategory-select" data-selected="<?php echo e($selectedModalSub); ?>" required></select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" value="<?php echo e(old('subject')); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required><?php echo e(old('description')); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Attachment</label>
                            <input type="file" name="attachment" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Assign To</label>
                            <select name="assigned_to_id" class="form-select">
                                <option value="">Unassigned</option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($u->id); ?>" <?php echo e(old('assigned_to_id') == $u->id ? 'selected' : ''); ?>><?php echo e($u->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Watchers</label>
                            <select name="watchers[]" class="form-select" multiple>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($u->id); ?>" <?php echo e(collect(old('watchers'))->contains($u->id) ? 'selected' : ''); ?>><?php echo e($u->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <small class="text-muted">Hold Ctrl or Command to select multiple users</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_at" class="form-control" value="<?php echo e(old('due_at')); ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="dynamicTicketModal" tabindex="-1" aria-hidden="true" role="dialog"></div>
    <?php echo $__env->make('partials.category-dropdown-script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalEl = document.getElementById('dynamicTicketModal');
        document.querySelectorAll('[data-details-url]').forEach(btn => {
            btn.addEventListener('click', () => {
                fetch(btn.dataset.detailsUrl)
                    .then(r => r.text())
                    .then(html => {
                        modalEl.innerHTML = html;
                        new bootstrap.Modal(modalEl).show();
                    });
            });
        });
        document.querySelectorAll('[data-edit-url]').forEach(btn => {
            btn.addEventListener('click', () => {
                fetch(btn.dataset.editUrl)
                    .then(r => r.text())
                    .then(html => {
                        modalEl.innerHTML = html;
                        new bootstrap.Modal(modalEl).show();
                    });
            });
        });
    });
    </script>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/tickets/index.blade.php ENDPATH**/ ?>