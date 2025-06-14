<?php $__env->startSection('title', 'Documents'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">My Documents</h1>
    <?php echo $__env->make('components.per-page-selector', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <a href="<?php echo e(route('documents.create')); ?>" class="btn btn-primary mb-3">Upload Document</a>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">My Documents</caption>
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Version</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($document->title); ?></td>
                <td><?php echo e($document->documentCategory->name); ?></td>
                <td><?php echo e($document->current_version); ?></td>
                <td>
                    <a href="<?php echo e(route('documents.show', $document)); ?>" class="btn btn-sm btn-info">View</a>
                    <a href="<?php echo e(route('documents.edit', $document)); ?>" class="btn btn-sm btn-primary">Edit</a>
                    <form action="<?php echo e(route('documents.destroy', $document)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this document?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    </div>
    <?php echo e($documents->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/documents/index.blade.php ENDPATH**/ ?>