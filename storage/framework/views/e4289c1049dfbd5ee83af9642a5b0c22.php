<?php $__env->startSection('title', 'Edit Requisition'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Edit Requisition</h1>
    <?php if($requisition->ticket_id): ?>
        <p><strong>Ticket ID:</strong>
            <a href="<?php echo e(route('tickets.index')); ?>#ticketModal<?php echo e($requisition->ticket_id); ?>">
                <?php echo e($requisition->ticket_id); ?>

            </a>
        </p>
    <?php endif; ?>
    <form action="<?php echo e(route('requisitions.update', $requisition)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div id="items-container">
            <?php $__currentLoopData = old('item', $requisition->items->pluck('item')->toArray()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="row g-2 mb-3 item-row">
                <div class="col-md-5">
                    <label class="form-label">Item</label>
                    <input type="text" name="item[]" class="form-control" value="<?php echo e(old('item.'.$i, $requisition->items[$i]->item ?? '')); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity[]" class="form-control" value="<?php echo e(old('quantity.'.$i, $requisition->items[$i]->quantity ?? 1)); ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Specification</label>
                    <input type="text" name="specification[]" class="form-control" value="<?php echo e(old('specification.'.$i, $requisition->items[$i]->specification ?? '')); ?>">
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <button type="button" id="add-item" class="btn btn-secondary mb-3">Add Item</button>
        <div class="mb-3">
            <label class="form-label">Purpose</label>
            <textarea name="purpose" class="form-control" rows="3" required><?php echo e(old('purpose', $requisition->purpose)); ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Remarks</label>
            <textarea name="remarks" class="form-control" rows="2"><?php echo e(old('remarks', $requisition->remarks)); ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Attachment</label>
            <input type="file" name="attachment" class="form-control">
            <?php if($requisition->attachment_path): ?>
                <small class="text-muted">Current: <a href="<?php echo e(route('requisitions.attachment', $requisition)); ?>" target="_blank">Download</a></small>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <?php ($statuses = [
                    \App\Models\Requisition::STATUS_PENDING_HEAD => 'Pending Head',
                    \App\Models\Requisition::STATUS_APPROVED => 'Approved',
                ]); ?>
                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value); ?>" <?php echo e(old('status', $requisition->status) === $value ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="<?php echo e(route('requisitions.index')); ?>" class="btn btn-secondary">Cancel</a>
    </form>
    <?php echo $__env->make('audit_trails._list', ['logs' => $requisition->auditTrails], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<script>
document.getElementById('add-item').addEventListener('click', function () {
    const container = document.getElementById('items-container');
    const row = container.querySelector('.item-row').cloneNode(true);
    row.querySelectorAll('input').forEach(input => input.value = input.type === 'number' ? 1 : '');
    container.appendChild(row);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/requisitions/edit.blade.php ENDPATH**/ ?>