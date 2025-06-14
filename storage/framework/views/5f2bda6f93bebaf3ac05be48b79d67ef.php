<?php $__env->startSection('title', 'Institution Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Institution Settings</h1>
    <form action="<?php echo e(route('settings.institution.update')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="mb-3">
            <label for="institution_address" class="form-label">Address</label>
            <textarea id="institution_address" name="institution_address" rows="3" class="form-control"><?php echo e($address); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="institution_phone" class="form-label">Phone</label>
            <input type="text" id="institution_phone" name="institution_phone" value="<?php echo e($phone); ?>" class="form-control" />
        </div>
        <div class="mb-3">
            <label for="helpdesk_email" class="form-label">Helpdesk Email</label>
            <input type="email" id="helpdesk_email" name="helpdesk_email" value="<?php echo e($email); ?>" class="form-control" />
        </div>
        <div class="mb-3">
            <label for="header_text" class="form-label">Header Text</label>
            <input type="text" id="header_text" name="header_text" value="<?php echo e($header_text); ?>" class="form-control" />
        </div>
        <div class="mb-3">
            <label for="footer_text" class="form-label">Footer Text</label>
            <input type="text" id="footer_text" name="footer_text" value="<?php echo e($footer_text); ?>" class="form-control" />
        </div>
        <div class="form-check form-switch mb-3">
            <input type="checkbox" id="show_footer" name="show_footer" value="1" class="form-check-input" <?php echo e($show_footer ? 'checked' : ''); ?>>
            <label for="show_footer" class="form-check-label">Show Footer</label>
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="<?php echo e(route('settings.index')); ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/settings/institution.blade.php ENDPATH**/ ?>