<?php $__env->startSection('title', 'Ticket Categories'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Ticket Categories</h1>
    <?php echo $__env->make('components.per-page-selector', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="mb-3 d-flex flex-wrap gap-2">
        <a href="<?php echo e(route('settings.index')); ?>" class="btn btn-secondary btn-sm">Back to Settings</a>
        <a href="<?php echo e(route('ticket-categories.create')); ?>" class="btn btn-sm btn-primary">Add Category</a>
    </div>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">Ticket Categories</caption>
        <thead>
            <tr>
                <th>Name</th>
                <th>Parent</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($category->name); ?></td>
                <td><?php echo e(optional($category->parent)->name); ?></td>
                <td>
                    <?php if($category->is_active): ?>
                        <span class="badge bg-success">Active</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Inactive</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="<?php echo e(route('ticket-categories.edit', $category)); ?>" class="btn btn-sm btn-primary">Edit</a>
                    <form action="<?php echo e(route('ticket-categories.destroy', $category)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    </div>
    <?php echo e($categories->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/settings/ticket-categories/index.blade.php ENDPATH**/ ?>