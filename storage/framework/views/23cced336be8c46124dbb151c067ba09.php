<?php $__env->startSection('title', 'Localization Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Localization Settings</h1>
    <form action="<?php echo e(route('settings.localization.update')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="mb-3">
            <label for="timezone" class="form-label">Timezone</label>
            <select id="timezone" name="timezone" class="form-select">
                <?php $__currentLoopData = $timezones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($tz); ?>" <?php echo e($timezone === $tz ? 'selected' : ''); ?>><?php echo e($tz); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label d-block">Date Format</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="format_ymd" name="date_format" value="Y-m-d" <?php echo e($date_format === 'Y-m-d' ? 'checked' : ''); ?>>
                <label class="form-check-label" for="format_ymd">YYYY-MM-DD</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="format_dmy" name="date_format" value="d/m/Y" <?php echo e($date_format === 'd/m/Y' ? 'checked' : ''); ?>>
                <label class="form-check-label" for="format_dmy">DD/MM/YYYY</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="<?php echo e(route('settings.index')); ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/settings/datetime.blade.php ENDPATH**/ ?>