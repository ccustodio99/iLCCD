<?php $__env->startSection('title', 'Notification Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Notification Settings</h1>
    <form action="<?php echo e(route('settings.notifications.update')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" role="switch" id="notify_ticket_updates" name="notify_ticket_updates" value="1" <?php echo e(setting('notify_ticket_updates', true) ? 'checked' : ''); ?>>
            <label class="form-check-label" for="notify_ticket_updates">Email Ticket Updates</label>
        </div>
        <div class="mb-3">
            <label for="template_ticket_updates" class="form-label">Ticket Update Template</label>
            <textarea id="template_ticket_updates" name="template_ticket_updates" rows="6" class="form-control"><?php echo e(setting('template_ticket_updates', '{{ message); ?>') }}</textarea>
        </div>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" role="switch" id="notify_job_order_status" name="notify_job_order_status" value="1" <?php echo e(setting('notify_job_order_status', true) ? 'checked' : ''); ?>>
            <label class="form-check-label" for="notify_job_order_status">Email Job Order Status</label>
        </div>
        <div class="mb-3">
            <label for="template_job_order_status" class="form-label">Job Order Template</label>
            <textarea id="template_job_order_status" name="template_job_order_status" rows="6" class="form-control"><?php echo e(setting('template_job_order_status', '{{ message); ?>') }}</textarea>
        </div>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" role="switch" id="notify_requisition_status" name="notify_requisition_status" value="1" <?php echo e(setting('notify_requisition_status', true) ? 'checked' : ''); ?>>
            <label class="form-check-label" for="notify_requisition_status">Email Requisition Status</label>
        </div>
        <div class="mb-3">
            <label for="template_requisition_status" class="form-label">Requisition Template</label>
            <textarea id="template_requisition_status" name="template_requisition_status" rows="6" class="form-control"><?php echo e(setting('template_requisition_status', '{{ message); ?>') }}</textarea>
        </div>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" role="switch" id="notify_low_stock" name="notify_low_stock" value="1" <?php echo e(setting('notify_low_stock', true) ? 'checked' : ''); ?>>
            <label class="form-check-label" for="notify_low_stock">Email Low Stock Alerts</label>
        </div>
        <div class="mb-3">
            <label for="template_low_stock" class="form-label">Low Stock Template</label>
            <textarea id="template_low_stock" name="template_low_stock" rows="6" class="form-control"><?php echo e(setting('template_low_stock', '{{ message); ?>') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="<?php echo e(route('settings.index')); ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css" />
<script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
<script>
    new EasyMDE({ element: document.getElementById('template_ticket_updates') });
    new EasyMDE({ element: document.getElementById('template_job_order_status') });
    new EasyMDE({ element: document.getElementById('template_requisition_status') });
    new EasyMDE({ element: document.getElementById('template_low_stock') });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/settings/notifications.blade.php ENDPATH**/ ?>