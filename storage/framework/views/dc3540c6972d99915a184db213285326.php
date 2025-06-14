<?php $__env->startSection('title', 'Add Job Order Type'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Add Job Order Type</h1>
    <form action="<?php echo e(route('job-order-types.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo e(old('name')); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Parent Type</label>
            <select name="parent_id" class="form-select">
                <option value="">None</option>
                <?php $__currentLoopData = $parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($parent->id); ?>" <?php echo e(old('parent_id') == $parent->id ? 'selected' : ''); ?>><?php echo e($parent->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
            <label for="is_active" class="form-check-label">Active</label>
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="<?php echo e(route('job-order-types.index')); ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/settings/job-order-types/create.blade.php ENDPATH**/ ?>