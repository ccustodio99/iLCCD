<?php if($transactions->isNotEmpty()): ?>
    <h6 class="mt-3">Recent Transactions</h6>
    <ul class="list-group mb-3">
        <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="list-group-item">
                <div class="d-flex justify-content-between">
                    <span><?php echo e($tx->created_at->format('Y-m-d H:i')); ?></span>
                    <span><?php echo e($tx->user?->name ?? 'System'); ?></span>
                    <span><?php echo e(ucfirst($tx->action)); ?></span>
                    <span><?php echo e($tx->quantity); ?></span>
                    <span><?php echo e($tx->purpose ?? '-'); ?></span>
                </div>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
<?php endif; ?>
<?php /**PATH E:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/inventory_transactions/_list.blade.php ENDPATH**/ ?>