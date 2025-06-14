<?php $__env->startSection('title', 'Theme Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Theme Settings</h1>
    <form action="<?php echo e(route('settings.theme.update')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="mb-3">
            <label for="color_primary" class="form-label">Primary Color</label>
            <input type="color" id="color_primary" name="color_primary" value="<?php echo e($primary); ?>" class="form-control form-control-color" />
        </div>
        <div class="mb-3">
            <label for="color_accent" class="form-label">Accent Color</label>
            <input type="color" id="color_accent" name="color_accent" value="<?php echo e($accent); ?>" class="form-control form-control-color" />
        </div>
        <div class="mb-3">
            <label for="font_primary" class="form-label">Primary Font</label>
            <select id="font_primary" name="font_primary" class="form-select">
                <?php $__currentLoopData = ['Poppins', 'Roboto', 'Montserrat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $font): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($font); ?>" <?php echo e($font_primary === $font ? 'selected' : ''); ?>><?php echo e($font); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="font_secondary" class="form-label">Secondary Font</label>
            <select id="font_secondary" name="font_secondary" class="form-select">
                <?php $__currentLoopData = ['Poppins', 'Roboto', 'Montserrat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $font): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($font); ?>" <?php echo e($font_secondary === $font ? 'selected' : ''); ?>><?php echo e($font); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="home_heading" class="form-label">Home Page Heading</label>
            <input type="text" id="home_heading" name="home_heading" value="<?php echo e($home_heading); ?>" class="form-control" />
        </div>
        <div class="mb-3">
            <label for="home_tagline" class="form-label">Home Page Tagline</label>
            <textarea id="home_tagline" name="home_tagline" rows="3" class="form-control"><?php echo e($home_tagline); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="<?php echo e(route('settings.index')); ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/settings/theme.blade.php ENDPATH**/ ?>