<?php $__env->startSection('title', 'Job Orders'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">My Job Orders</h1>
    <?php echo $__env->make('components.per-page-selector', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#newJobOrderModal">New Job Order</button>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">Job Orders</caption>
        <thead>
            <tr>
                <th>Type</th>
                <th>Description</th>
                <th>Status</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $jobOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jobOrder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($jobOrder->job_type); ?></td>
                <td><?php echo e(Str::limit($jobOrder->description, 50)); ?></td>
                <td><?php echo e(ucfirst($jobOrder->status)); ?></td>
                <td><?php echo e($jobOrder->user_id === auth()->id() ? 'Requester' : 'Assignee'); ?></td>
                <td>
                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#jobOrderModal<?php echo e($jobOrder->id); ?>">View</button>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editJobOrderModal<?php echo e($jobOrder->id); ?>">Edit</button>
                    <?php if($jobOrder->status === \App\Models\JobOrder::STATUS_COMPLETED): ?>
                        <form action="<?php echo e(route('job-orders.close', $jobOrder)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Close this job order?')">Close</button>
                        </form>
                    <?php elseif($jobOrder->user_id === auth()->id()): ?>
                        <form action="<?php echo e(route('job-orders.complete', $jobOrder)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Mark this job order as complete?')">Job Complete</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    </div>
    <?php echo e($jobOrders->links()); ?>


    <?php $__currentLoopData = $jobOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jobOrder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="jobOrderModal<?php echo e($jobOrder->id); ?>" tabindex="-1" aria-labelledby="jobOrderModalLabel<?php echo e($jobOrder->id); ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jobOrderModalLabel<?php echo e($jobOrder->id); ?>">Job Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Type:</strong> <?php echo e($jobOrder->job_type); ?></p>
                    <p><strong>Description:</strong> <?php echo e($jobOrder->description); ?></p>
                    <p><strong>Status:</strong> <?php echo e(ucfirst($jobOrder->status)); ?></p>
                    <p><strong>Role:</strong> <?php echo e($jobOrder->user_id === auth()->id() ? 'Requester' : 'Assignee'); ?></p>
                    <?php if($jobOrder->ticket_id): ?>
                        <p><strong>Ticket ID:</strong>
                            <a href="<?php echo e(route('tickets.index')); ?>#ticketModal<?php echo e($jobOrder->ticket_id); ?>">
                                <?php echo e($jobOrder->ticket_id); ?>

                            </a>
                        </p>
                    <?php endif; ?>
                    <p><strong>Approved At:</strong> <?php echo e($jobOrder->approved_at?->format('Y-m-d H:i') ?? '-'); ?></p>
                    <p><strong>Started At:</strong> <?php echo e($jobOrder->started_at?->format('Y-m-d H:i') ?? '-'); ?></p>
                    <p><strong>Completed At:</strong> <?php echo e($jobOrder->completed_at?->format('Y-m-d H:i') ?? '-'); ?></p>
                    <?php if($jobOrder->attachment_path): ?>
                        <p><strong>Attachment:</strong> <a href="<?php echo e(route('job-orders.attachment', $jobOrder)); ?>" target="_blank">Download</a></p>
                    <?php endif; ?>
                    <?php if($jobOrder->requisitions->count()): ?>
                        <h6>Requisitions</h6>
                        <ul>
                            <?php $__currentLoopData = $jobOrder->requisitions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <ul class="mb-0">
                                        <?php $__currentLoopData = $req->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($item->item); ?> (<?php echo e($item->quantity); ?>)</li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                    <span class="ms-2">(<?php echo e(ucfirst($req->status)); ?>)</span>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php endif; ?>
                    <?php echo $__env->make('audit_trails._list', ['logs' => $jobOrder->auditTrails], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php ($child = \App\Models\JobOrderType::where('name', $jobOrder->job_type)->first()); ?>
    <?php ($parentIdCurrent = $child?->parent_id); ?>
    <div class="modal fade" id="editJobOrderModal<?php echo e($jobOrder->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Job Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo e(route('job-orders.update', $jobOrder)); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type_parent" id="type_parent_<?php echo e($jobOrder->id); ?>" class="form-select" required>
                                <option value="">Select Type</option>
                                <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($type->id); ?>" <?php echo e(old('type_parent', $parentIdCurrent) == $type->id ? 'selected' : ''); ?>><?php echo e($type->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sub Type</label>
                            <select name="job_type" id="job_type_<?php echo e($jobOrder->id); ?>" class="form-select" required disabled>
                                <option value="">Select Sub Type</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required><?php echo e(old('description', $jobOrder->description)); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Attachment</label>
                            <input type="file" name="attachment" class="form-control">
                            <?php if($jobOrder->attachment_path): ?>
                                <small class="text-muted">Current: <a href="<?php echo e(route('job-orders.attachment', $jobOrder)); ?>" target="_blank">Download</a></small>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <?php ($statuses = [
                                    'pending_head' => 'Pending Head',
                                    'pending_president' => 'Pending President',
                                    'pending_finance' => 'Pending Finance',
                                    'approved' => 'Approved',
                                    'assigned' => 'Assigned',
                                    'in_progress' => 'In Progress',
                                    'completed' => 'Completed',
                                    'closed' => 'Closed'
                                ]); ?>
                                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>" <?php echo e(old('status', $jobOrder->status) === $value ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const parent<?php echo e($jobOrder->id); ?> = document.getElementById('type_parent_<?php echo e($jobOrder->id); ?>');
                    const child<?php echo e($jobOrder->id); ?> = document.getElementById('job_type_<?php echo e($jobOrder->id); ?>');

                    function loadChildren<?php echo e($jobOrder->id); ?>(id, selected) {
                        child<?php echo e($jobOrder->id); ?>.innerHTML = '<option value="">Select Sub Type</option>';
                        if (!id) {
                            child<?php echo e($jobOrder->id); ?>.disabled = true;
                            return;
                        }
                        child<?php echo e($jobOrder->id); ?>.disabled = false;
                        fetch(`/job-order-types/${id}/children`)
                            .then(r => r.json())
                            .then(data => {
                                data.forEach(c => {
                                    const opt = document.createElement('option');
                                    opt.value = c.name;
                                    opt.textContent = c.name;
                                    if (selected === c.name) opt.selected = true;
                                    child<?php echo e($jobOrder->id); ?>.appendChild(opt);
                                });
                            });
                    }

                    parent<?php echo e($jobOrder->id); ?>.addEventListener('change', () => loadChildren<?php echo e($jobOrder->id); ?>(parent<?php echo e($jobOrder->id); ?>.value));
                    loadChildren<?php echo e($jobOrder->id); ?>(parent<?php echo e($jobOrder->id); ?>.value, '<?php echo e(old('job_type', $jobOrder->job_type)); ?>');
                });
                </script>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <div class="modal fade" id="newJobOrderModal" tabindex="-1" aria-labelledby="newJobOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newJobOrderModalLabel">New Job Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo e(route('job-orders.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type_parent" id="new_type_parent" class="form-select" required>
                                <option value="">Select Type</option>
                                <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($type->id); ?>" <?php echo e(old('type_parent') == $type->id ? 'selected' : ''); ?>><?php echo e($type->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sub Type</label>
                            <select name="job_type" id="new_job_type" class="form-select" required disabled>
                                <option value="">Select Sub Type</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required><?php echo e(old('description')); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Attachment</label>
                            <input type="file" name="attachment" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const parent = document.getElementById('new_type_parent');
    const child = document.getElementById('new_job_type');

    function loadChildren(id, selected) {
        child.innerHTML = '<option value="">Select Sub Type</option>';
        if (!id) {
            child.disabled = true;
            return;
        }
        child.disabled = false;
        fetch(`/job-order-types/${id}/children`)
            .then(r => r.json())
            .then(data => {
                data.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.name;
                    opt.textContent = c.name;
                    if (selected === c.name) opt.selected = true;
                    child.appendChild(opt);
                });
            });
    }

    parent.addEventListener('change', () => loadChildren(parent.value));
    loadChildren(parent.value, '<?php echo e(old('job_type')); ?>');
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/job_orders/index.blade.php ENDPATH**/ ?>