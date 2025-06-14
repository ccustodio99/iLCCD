<?php $__env->startSection('title', 'Branding'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Branding</h1>
    <form action="<?php echo e(route('settings.branding.update')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="mb-3">
            <label for="logo" class="form-label">Logo</label>
            <?php if(setting('logo_path')): ?>
                <div class="mb-2">
                    <img src="<?php echo e(asset(setting('logo_path'))); ?>" alt="Current Logo" style="max-height: 80px;">
                </div>
            <?php endif; ?>
            <input type="file" id="logo" name="logo" class="form-control">
        </div>
        <div class="mb-3">
            <label for="favicon" class="form-label">Favicon</label>
            <?php if(setting('favicon_path')): ?>
                <div class="mb-2">
                    <img src="<?php echo e(asset(setting('favicon_path'))); ?>" alt="Current Favicon" style="max-height: 32px;">
                </div>
            <?php endif; ?>
            <input type="file" id="favicon" name="favicon" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="<?php echo e(route('settings.index')); ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/settings/branding.blade.php ENDPATH**/ ?>