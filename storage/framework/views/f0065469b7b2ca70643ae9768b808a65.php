<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">New Job Order</h1>
    <form action="<?php echo e(route('job-orders.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label class="form-label">Type</label>
            <input type="text" name="job_type" class="form-control" value="<?php echo e(old('job_type')); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" required><?php echo e(old('description')); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/job_orders/create.blade.php ENDPATH**/ ?>