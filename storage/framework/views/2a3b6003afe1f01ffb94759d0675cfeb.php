<?php $__env->startSection('title', 'Audit Trail'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Audit Trail</h1>
    <?php echo $__env->make('components.per-page-selector', ['default' => 20], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="mb-3">
        <form method="GET" class="row row-cols-lg-auto g-2 align-items-end">
            <div class="col">
                <label for="filter-user" class="form-label">User</label>
                <select id="filter-user" name="user_id" class="form-select">
                    <option value="">Any</option>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($u->id); ?>" <?php if((string)request('user_id') === (string)$u->id): echo 'selected'; endif; ?>><?php echo e($u->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col">
                <label for="filter-module" class="form-label">Module</label>
                <select id="filter-module" name="auditable_type" class="form-select">
                    <option value="">Any</option>
                    <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($module); ?>" <?php if(request('auditable_type') === $module): echo 'selected'; endif; ?>><?php echo e(class_basename($module)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col">
                <label for="filter-action" class="form-label">Action</label>
                <select id="filter-action" name="action" class="form-select">
                    <option value="">Any</option>
                    <?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($action); ?>" <?php if(request('action') === $action): echo 'selected'; endif; ?>><?php echo e(ucfirst(str_replace('_', ' ', $action))); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col">
                <label for="filter-from" class="form-label">From</label>
                <input id="filter-from" type="date" name="from" value="<?php echo e(request('from')); ?>" class="form-control">
            </div>
            <div class="col">
                <label for="filter-to" class="form-label">To</label>
                <input id="filter-to" type="date" name="to" value="<?php echo e(request('to')); ?>" class="form-control">
            </div>
            <div class="col">
                <button type="submit" class="btn btn-secondary">Filter</button>
            </div>
        </form>
    </div>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/audit_trails/index.blade.php ENDPATH**/ ?>