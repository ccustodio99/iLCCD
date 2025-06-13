<?php $__env->startSection('title', 'New Requisition'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">New Requisition</h1>
    <form action="<?php echo e(route('requisitions.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div id="items-container">
            <div class="row g-2 mb-3 item-row">
                <div class="col-md-5">
                    <label class="form-label">Item</label>
                    <input type="text" name="item[]" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity[]" class="form-control" value="1" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Specification</label>
                    <input type="text" name="specification[]" class="form-control">
                </div>
            </div>
        </div>
        <button type="button" id="add-item" class="btn btn-secondary mb-3">Add Item</button>
        <div class="mb-3">
            <label class="form-label">Purpose</label>
            <textarea name="purpose" class="form-control" rows="3" required><?php echo e(old('purpose')); ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Remarks</label>
            <textarea name="remarks" class="form-control" rows="2"><?php echo e(old('remarks')); ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Attachment</label>
            <input type="file" name="attachment" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary me-2">Submit</button>
        <a href="<?php echo e(route('requisitions.index')); ?>" class="btn btn-secondary">Cancel</a>
    </form>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/requisitions/create.blade.php ENDPATH**/ ?>