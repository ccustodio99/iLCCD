<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Edit Purchase Order</h1>
    <form action="<?php echo e(route('purchase-orders.update', $purchaseOrder)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="mb-3">
            <label class="form-label">Requisition ID</label>
            <input type="number" name="requisition_id" class="form-control" value="<?php echo e(old('requisition_id', $purchaseOrder->requisition_id)); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Inventory Item ID</label>
            <input type="number" name="inventory_item_id" class="form-control" value="<?php echo e(old('inventory_item_id', $purchaseOrder->inventory_item_id)); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Item</label>
            <input type="text" name="item" class="form-control" value="<?php echo e(old('item', $purchaseOrder->item)); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" value="<?php echo e(old('quantity', $purchaseOrder->quantity)); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <?php ($statuses = ['draft' => 'Draft', 'ordered' => 'Ordered', 'received' => 'Received']); ?>
                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value); ?>" <?php echo e(old('status', $purchaseOrder->status) === $value ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
    <?php echo $__env->make('audit_trails._list', ['logs' => $purchaseOrder->auditTrails], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/purchase_orders/edit.blade.php ENDPATH**/ ?>