<?php $__env->startSection('title', 'KPI & Audit Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">System KPI & Audit Dashboard</h1>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Tickets</h5>
                    <p class="display-6"><?php echo e($ticketsCount); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Job Orders</h5>
                    <p class="display-6"><?php echo e($jobOrdersCount); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Requisitions</h5>
                    <p class="display-6"><?php echo e($requisitionsCount); ?></p>
                </div>
            </div>
        </div>
    </div>
    <h3>Recent Audit Logs</h3>
    <?php echo $__env->make('components.per-page-selector', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">Recent Audit Logs</caption>
        <thead>
            <tr>
                <th>Date</th>
                <th>User</th>
                <th>Model</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($log->created_at->format('Y-m-d H:i')); ?></td>
                <td><?php echo e($log->user?->name ?? 'System'); ?></td>
                <td><?php echo e(class_basename($log->auditable_type)); ?>#<?php echo e($log->auditable_id); ?></td>
                <td><?php echo e($log->action); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    </div>
    <?php echo e($logs->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/kpi/dashboard.blade.php ENDPATH**/ ?>