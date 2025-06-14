<?php $__env->startSection('title', 'Requisitions'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">My Requisitions</h1>
    <?php echo $__env->make('components.per-page-selector', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="mb-3">
        <form method="GET" class="row row-cols-lg-auto g-2 align-items-end">
            <div class="col">
                <label for="filter-status" class="form-label">Status</label>
                <select id="filter-status" name="status" class="form-select">
                    <option value="">Any</option>
                    <?php if(isset($statuses)): ?>
                        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($status); ?>" <?php if(request('status') === $status): echo 'selected'; endif; ?>>
                                <?php echo e(ucfirst(str_replace('_', ' ', $status))); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col">
                <label for="filter-ticket" class="form-label">Ticket ID</label>
                <input id="filter-ticket" type="number" name="ticket_id" value="<?php echo e(request('ticket_id')); ?>" class="form-control">
            </div>
            <div class="col">
                <label for="filter-search" class="form-label">Search</label>
                <input id="filter-search" type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="Purpose, remarks or item">
            </div>
            <div class="col">
                <button type="submit" class="btn btn-secondary">Filter</button>
            </div>
        </form>
    </div>
    <a href="<?php echo e(route('requisitions.create')); ?>" class="btn btn-primary mb-3">New Requisition</a>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">My Requisitions</caption>
        <thead>
            <tr>
                <th>Items</th>
                <th>Status</th>
                <th>Ticket</th>
                <th>Remarks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $requisitions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $requisition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td>
                    <ul class="mb-0">
                        <?php $__currentLoopData = $requisition->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($item->item); ?> (<?php echo e($item->quantity); ?>)</li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </td>
                <td><?php echo e(ucfirst(str_replace('_', ' ', $requisition->status))); ?></td>
                <td>
                    <?php if($requisition->ticket_id): ?>
                        <a href="<?php echo e(route('tickets.index')); ?>#ticketModal<?php echo e($requisition->ticket_id); ?>">#<?php echo e($requisition->ticket_id); ?></a>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td><?php echo e(Str::limit($requisition->remarks, 50)); ?></td>
                <td>
                    <a href="<?php echo e(route('requisitions.edit', $requisition)); ?>" class="btn btn-sm btn-primary">Edit</a>
                    <form action="<?php echo e(route('requisitions.destroy', $requisition)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this requisition?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    </div>
    <?php echo e($requisitions->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/requisitions/index.blade.php ENDPATH**/ ?>