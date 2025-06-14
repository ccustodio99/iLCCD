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
    <div class="mb-3">
        <form method="GET" class="row row-cols-lg-auto g-2 align-items-end">
            <div class="col">
                <label for="filter-date-from" class="form-label">From</label>
                <input id="filter-date-from" type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" class="form-control">
            </div>
            <div class="col">
                <label for="filter-date-to" class="form-label">To</label>
                <input id="filter-date-to" type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" class="form-control">
            </div>
            <div class="col">
                <label for="filter-user" class="form-label">User</label>
                <select id="filter-user" name="user_id" class="form-select">
                    <option value="">All</option>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($u->id); ?>" <?php if((string) request('user_id') === (string) $u->id): echo 'selected'; endif; ?>><?php echo e($u->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col">
                <label for="filter-department" class="form-label">Department</label>
                <input id="filter-department" type="text" name="department" value="<?php echo e(request('department')); ?>" class="form-control">
            </div>
            <div class="col">
                <label for="filter-module" class="form-label">Module</label>
                <select id="filter-module" name="module" class="form-select">
                    <option value="">All</option>
                    <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>" <?php if(request('module') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col">
                <label for="filter-action" class="form-label">Action</label>
                <select id="filter-action" name="action" class="form-select">
                    <option value="">All</option>
                    <?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $act): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($act); ?>" <?php if(request('action') === $act): echo 'selected'; endif; ?>><?php echo e(ucfirst($act)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col">
                <button type="submit" class="btn btn-secondary">Filter</button>
            </div>
        </form>
    </div>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/kpi/dashboard.blade.php ENDPATH**/ ?>