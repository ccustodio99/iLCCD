<?php $__env->startSection('title', 'Add Inventory Item'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Add Inventory Item</h1>
    <form action="<?php echo e(route('inventory.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo e(old('name')); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3"><?php echo e(old('description')); ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="inventory_category_id" class="form-select">
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat->id); ?>" <?php echo e(old('inventory_category_id') == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Department</label>
            <input type="text" name="department" class="form-control" value="<?php echo e(old('department')); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" value="<?php echo e(old('location')); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Supplier</label>
            <input type="text" name="supplier" class="form-control" value="<?php echo e(old('supplier')); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Purchase Date</label>
            <input type="date" name="purchase_date" class="form-control" value="<?php echo e(old('purchase_date')); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" value="<?php echo e(old('quantity')); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Minimum Stock</label>
            <input type="number" name="minimum_stock" class="form-control" value="<?php echo e(old('minimum_stock')); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <?php ($statuses = [
                    \App\Models\InventoryItem::STATUS_AVAILABLE => 'Available',
                    \App\Models\InventoryItem::STATUS_RESERVED => 'Reserved',
                    \App\Models\InventoryItem::STATUS_MAINTENANCE => 'Maintenance',
                    \App\Models\InventoryItem::STATUS_RETIRED => 'Retired',
                ]); ?>
                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value); ?>" <?php echo e(old('status', \App\Models\InventoryItem::STATUS_AVAILABLE) === $value ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="<?php echo e(route('inventory.index')); ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/inventory/create.blade.php ENDPATH**/ ?>