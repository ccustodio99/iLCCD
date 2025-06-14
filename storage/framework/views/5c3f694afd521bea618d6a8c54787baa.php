<?php $__env->startSection('title', 'Inventory'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">My Inventory Items</h1>
    <?php echo $__env->make('components.per-page-selector', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="mb-3">
        <form method="GET" class="row row-cols-lg-auto g-2 align-items-end">
            <div class="col">
                <label for="filter-category" class="form-label">Category</label>
                <select id="filter-category" name="category" class="form-select">
                    <option value="">All</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->id); ?>" <?php if((string) request('category') === (string) $category->id): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col">
                <label for="filter-status" class="form-label">Status</label>
                <select id="filter-status" name="status" class="form-select">
                    <option value="">Any</option>
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($status); ?>" <?php if(request('status') === $status): echo 'selected'; endif; ?>><?php echo e(ucfirst($status)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col">
                <label for="filter-search" class="form-label">Search</label>
                <input id="filter-search" type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="Name">
            </div>
            <div class="col">
                <button type="submit" class="btn btn-secondary">Filter</button>
            </div>
        </form>
    </div>
    <a href="<?php echo e(route('inventory.create')); ?>" class="btn btn-primary mb-3">Add Item</a>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">Inventory Items</caption>
        <thead>
            <tr>
                <th>Name</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="<?php echo e($item->quantity == 0 ? 'table-danger' : ($item->quantity <= $item->minimum_stock ? 'table-warning' : '')); ?>">
                <td><?php echo e($item->name); ?></td>
                <td>
                    <?php if($item->quantity == 0): ?>
                        <span class="badge bg-danger">0</span>
                    <?php elseif($item->quantity <= $item->minimum_stock): ?>
                        <span class="badge bg-warning text-dark"><?php echo e($item->quantity); ?></span>
                    <?php else: ?>
                        <?php echo e($item->quantity); ?>

                    <?php endif; ?>
                </td>
                <td><?php echo e(ucfirst($item->status)); ?></td>
                <td>
                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#inventoryItemModal<?php echo e($item->id); ?>">Details</button>
                    <a href="<?php echo e(route('inventory.edit', $item)); ?>" class="btn btn-sm btn-primary">Edit</a>
                    <form action="<?php echo e(route('inventory.destroy', $item)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this item?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    </div>
    <?php echo e($items->links()); ?>


    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="inventoryItemModal<?php echo e($item->id); ?>" tabindex="-1" aria-labelledby="inventoryItemModalLabel<?php echo e($item->id); ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inventoryItemModalLabel<?php echo e($item->id); ?>">Item Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Name:</strong> <?php echo e($item->name); ?></p>
                    <p><strong>Description:</strong> <?php echo e($item->description); ?></p>
                    <p><strong>Category:</strong> <?php echo e(optional($item->inventoryCategory)->name); ?></p>
                    <p><strong>Department:</strong> <?php echo e($item->department); ?></p>
                    <p><strong>Location:</strong> <?php echo e($item->location); ?></p>
                    <p><strong>Supplier:</strong> <?php echo e($item->supplier); ?></p>
                    <p><strong>Purchase Date:</strong> <?php echo e(optional($item->purchase_date)->format('Y-m-d')); ?></p>
                    <p><strong>Quantity:</strong> <?php echo e($item->quantity); ?></p>
                    <p><strong>Minimum Stock:</strong> <?php echo e($item->minimum_stock); ?></p>
                    <p><strong>Status:</strong> <?php echo e(ucfirst($item->status)); ?></p>

                    <form action="<?php echo e(route('inventory.issue', $item)); ?>" method="POST" class="row g-2 mb-2">
                        <?php echo csrf_field(); ?>
                        <div class="col-auto">
                            <input type="number" name="quantity" min="1" value="1" class="form-control form-control-sm" aria-label="Quantity to issue">
                        </div>
                        <div class="col-auto">
                            <input type="text" name="purpose" class="form-control form-control-sm" placeholder="Purpose" aria-label="Purpose">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-warning btn-sm">Issue</button>
                        </div>
                    </form>
                    <form action="<?php echo e(route('inventory.return', $item)); ?>" method="POST" class="row g-2">
                        <?php echo csrf_field(); ?>
                        <div class="col-auto">
                            <input type="number" name="quantity" min="1" value="1" class="form-control form-control-sm" aria-label="Quantity to return">
                        </div>
                        <div class="col-auto">
                            <input type="text" name="purpose" class="form-control form-control-sm" placeholder="Purpose" aria-label="Purpose">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-success btn-sm">Return</button>
                        </div>
                    </form>

                    <?php echo $__env->make('inventory_transactions._list', ['transactions' => $item->transactions], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                    <?php echo $__env->make('audit_trails._list', ['logs' => $item->auditTrails], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/inventory/index.blade.php ENDPATH**/ ?>