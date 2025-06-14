<div class="mb-2">
    <form method="GET" class="d-inline-flex align-items-center">
        <?php $__currentLoopData = request()->except(['per_page', 'page']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <input type="hidden" name="<?php echo e($name); ?>" value="<?php echo e($value); ?>">
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <label for="per-page" class="me-2">Items per page</label>
        <select id="per-page" name="per_page" class="form-select w-auto" onchange="this.form.submit()">
            <?php $__currentLoopData = ($options ?? [5,10,20,50]); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($option); ?>" <?php if((int) request('per_page', $default ?? 10) === $option): echo 'selected'; endif; ?>><?php echo e($option); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </form>
</div>

<?php /**PATH D:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/components/per-page-selector.blade.php ENDPATH**/ ?>