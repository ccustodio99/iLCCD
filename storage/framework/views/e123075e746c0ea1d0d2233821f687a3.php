<?php $__env->startSection('title', 'Document Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Document KPI & Log Dashboard</h1>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Uploads</h5>
                    <p class="display-6"><?php echo e($totalUploads); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Versions</h5>
                    <p class="display-6"><?php echo e($totalVersions); ?></p>
                </div>
            </div>
        </div>
    </div>
    <h3>Recent Activity</h3>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">Recent Document Activity</caption>
        <thead>
            <tr>
                <th>Document</th>
                <th>User</th>
                <th>Action</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $recentLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($log->document->title); ?></td>
                <td><?php echo e($log->user->name); ?></td>
                <td><?php echo e(ucfirst($log->action)); ?></td>
                <td><?php echo e($log->created_at->format('Y-m-d H:i')); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/documents/dashboard.blade.php ENDPATH**/ ?>