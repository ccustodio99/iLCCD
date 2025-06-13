<?php $__env->startSection('title', 'Audit Trail'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Audit Trail</h1>
    <?php echo $__env->make('components.per-page-selector', ['default' => 20], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">Audit Trail</caption>
        <thead>
            <tr>
                <th>Date</th>
                <th>User</th>
                <th>Model</th>
                <th>Action</th>
                <th>Changes</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($log->created_at->format('Y-m-d H:i')); ?></td>
                <td><?php echo e($log->user?->name ?? 'System'); ?></td>
                <td><?php echo e(class_basename($log->auditable_type)); ?>#<?php echo e($log->auditable_id); ?></td>
                <td><?php echo e($log->action); ?></td>
                <td>
                    <?php if($log->changes): ?>
                        <ul class="list-unstyled mb-0">
                            <?php $__currentLoopData = $log->changes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $values): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $label = $field;
                                    $old = $values['old'];
                                    $new = $values['new'];
                                    if ($field === 'assigned_to_id') {
                                        $label = 'assigned_to';
                                        $old = \App\Models\User::find($values['old'])?->name;
                                        $new = \App\Models\User::find($values['new'])?->name;
                                    }
                                ?>
                                <li><?php echo e($label); ?>: <?php echo e($old); ?> → <?php echo e($new); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    </div>
    <?php echo e($logs->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/audit_trails/index.blade.php ENDPATH**/ ?>