<?php if($logs->isNotEmpty()): ?>
    <h6 class="mt-3">Audit Trail</h6>
    <ul class="list-group mb-3">
        <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="list-group-item">
                <div class="d-flex justify-content-between">
                    <span><?php echo e($log->created_at->format('Y-m-d H:i')); ?></span>
                    <span><?php echo e($log->user?->name ?? 'System'); ?></span>
                    <span><?php echo e(ucfirst($log->action)); ?></span>
                </div>
                <?php if($log->changes): ?>
                    <ul class="list-unstyled small mb-0 mt-1">
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
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
<?php endif; ?>
<?php /**PATH D:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/audit_trails/_list.blade.php ENDPATH**/ ?>