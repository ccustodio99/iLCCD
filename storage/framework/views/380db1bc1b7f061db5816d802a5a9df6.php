<?php $__env->startSection('title', 'Purchase Orders'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Purchase Orders</h1>
    <?php echo $__env->make('components.per-page-selector', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <a href="<?php echo e(route('purchase-orders.create')); ?>" class="btn btn-primary mb-3">New Purchase Order</a>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">Purchase Orders</caption>
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Supplier</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($order->item); ?></td>
                <td><?php echo e($order->quantity); ?></td>
                <td><?php echo e($order->supplier); ?></td>
                <td><?php echo e(ucfirst($order->status)); ?></td>
                <td>
                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#purchaseOrderModal<?php echo e($order->id); ?>">Details</button>
                    <a href="<?php echo e(route('purchase-orders.edit', $order)); ?>" class="btn btn-sm btn-primary">Edit</a>
                    <form action="<?php echo e(route('purchase-orders.destroy', $order)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this purchase order?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    </div>
    <?php echo e($orders->links()); ?>


    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="purchaseOrderModal<?php echo e($order->id); ?>" tabindex="-1" aria-labelledby="purchaseOrderModalLabel<?php echo e($order->id); ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="purchaseOrderModalLabel<?php echo e($order->id); ?>">Purchase Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Item:</strong> <?php echo e($order->item); ?></p>
                    <p><strong>Quantity:</strong> <?php echo e($order->quantity); ?></p>
                    <p><strong>Supplier:</strong> <?php echo e($order->supplier); ?></p>
                    <p><strong>Status:</strong> <?php echo e(ucfirst($order->status)); ?></p>
                    <p><strong>Requisition ID:</strong> <?php echo e($order->requisition_id); ?></p>
                    <p><strong>Inventory Item ID:</strong> <?php echo e($order->inventory_item_id); ?></p>
                    <p><strong>Ordered At:</strong> <?php echo e(optional($order->ordered_at)->format('Y-m-d H:i')); ?></p>
                    <p><strong>Received At:</strong> <?php echo e(optional($order->received_at)->format('Y-m-d H:i')); ?></p>
                    <?php if($order->attachment_path): ?>
                        <p><a href="<?php echo e(route('purchase-orders.attachment', $order)); ?>" target="_blank">Download Attachment</a></p>
                    <?php endif; ?>
                    <?php echo $__env->make('audit_trails._list', ['logs' => $order->auditTrails], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/purchase_orders/index.blade.php ENDPATH**/ ?>