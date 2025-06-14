<?php $__env->startSection('title', 'Create Purchase Order'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Create Purchase Order</h1>
    <form action="<?php echo e(route('purchase-orders.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label class="form-label">Requisition ID</label>
            <input type="number" name="requisition_id" class="form-control" value="<?php echo e(old('requisition_id')); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Inventory Item ID</label>
            <input type="number" name="inventory_item_id" class="form-control" value="<?php echo e(old('inventory_item_id')); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Supplier</label>
            <input type="text" name="supplier" class="form-control" value="<?php echo e(old('supplier')); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Item</label>
            <input type="text" name="item" class="form-control" value="<?php echo e(old('item')); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" value="<?php echo e(old('quantity')); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Attachment</label>
            <input type="file" name="attachment" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="<?php echo e(route('purchase-orders.index')); ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/purchase_orders/create.blade.php ENDPATH**/ ?>