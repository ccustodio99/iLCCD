<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Edit Job Order</h1>
    <form action="<?php echo e(route('job-orders.update', $jobOrder)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="mb-3">
            <label class="form-label">Type</label>
            <input type="text" name="job_type" class="form-control" value="<?php echo e(old('job_type', $jobOrder->job_type)); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" required><?php echo e(old('description', $jobOrder->description)); ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <?php ($statuses = ['new' => 'New', 'approved' => 'Approved', 'assigned' => 'Assigned', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'closed' => 'Closed']); ?>
                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value); ?>" <?php echo e(old('status', $jobOrder->status) === $value ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/job_orders/edit.blade.php ENDPATH**/ ?>