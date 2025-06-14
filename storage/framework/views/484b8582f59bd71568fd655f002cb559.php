<?php $__env->startSection('title', 'Job Order Types'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Job Order Types</h1>
    <?php echo $__env->make('components.per-page-selector', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <a href="<?php echo e(route('job-order-types.create')); ?>" class="btn btn-sm btn-primary mb-3">Add Type</a>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">Job Order Types</caption>
        <thead>
            <tr>
                <th>Name</th>
                <th>Parent</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($type->name); ?></td>
                <td><?php echo e(optional($type->parent)->name); ?></td>
                <td>
                    <?php if($type->is_active): ?>
                        <span class="badge bg-success">Active</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Inactive</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="<?php echo e(route('job-order-types.edit', $type)); ?>" class="btn btn-sm btn-primary">Edit</a>
                    <?php if($type->is_active): ?>
                        <form action="<?php echo e(route('job-order-types.disable', $type)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <button type="submit" class="btn btn-sm btn-warning">Disable</button>
                        </form>
                    <?php endif; ?>
                    <form action="<?php echo e(route('job-order-types.destroy', $type)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this type?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    </div>
    <?php echo e($types->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/settings/job-order-types/index.blade.php ENDPATH**/ ?>