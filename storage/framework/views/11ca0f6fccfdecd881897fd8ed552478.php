<?php use Illuminate\Support\Str; ?>

<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4 text-center">Dashboard</h1>

    <?php if($announcements->count()): ?>
    <div class="mb-4">
        <h2>Announcements</h2>
        <ul class="list-group">
            <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announce): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="list-group-item">
                    <strong><?php echo e($announce->title); ?></strong><br>
                    <span class="small text-muted"><?php echo e(Str::limit($announce->content, 100)); ?></span>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <h2 class="mt-4">Pending Tickets</h2>
    <form method="GET" class="mb-2">
        <?php $__currentLoopData = request()->except(['ticket_status','tickets_page','job_orders_page','requisitions_page','purchase_orders_page','incoming_docs_page','outgoing_docs_page','for_approval_docs_page']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <input type="hidden" name="<?php echo e($name); ?>" value="<?php echo e($value); ?>">
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <select name="ticket_status" class="form-select w-auto d-inline" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            <?php $__currentLoopData = ['open' => 'Open', 'escalated' => 'Escalated', 'converted' => 'Converted']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($val); ?>" <?php echo e(request('ticket_status') === $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </form>
    <div class="table-responsive">
    <table class="table table-striped" id="tickets-table">
    <caption class="visually-hidden">Pending Tickets</caption>
        <thead>
            <tr>
                <th>Subject</th>
                <th>Status</th>
                <th>Due</th>
            </tr>
        </thead>
        <tbody id="tickets-body">
            <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($ticket->formatted_subject); ?></td>
                <td><?php echo e(ucfirst($ticket->status)); ?></td>
                <td><?php echo e(optional($ticket->due_at)->format('Y-m-d')); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="3" class="text-center">No records</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
    <?php echo e($tickets->appends(request()->except(['tickets_page','job_orders_page','requisitions_page','purchase_orders_page','incoming_docs_page','outgoing_docs_page','for_approval_docs_page']))->links()); ?>


    <h2 class="mt-5">Pending Job Orders</h2>
    <form method="GET" class="mb-2">
        <?php $__currentLoopData = request()->except(['job_status','tickets_page','job_orders_page','requisitions_page','purchase_orders_page','incoming_docs_page','outgoing_docs_page','for_approval_docs_page']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <input type="hidden" name="<?php echo e($name); ?>" value="<?php echo e($value); ?>">
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <select name="job_status" class="form-select w-auto d-inline" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            <?php $__currentLoopData = [
                'pending_head' => 'Pending Head',
                'pending_president' => 'Pending President',
                'pending_finance' => 'Pending Finance',
                'approved' => 'Approved',
                'assigned' => 'Assigned',
                'in_progress' => 'In Progress',
                'completed' => 'Completed',
                'closed' => 'Closed'
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($val); ?>" <?php echo e(request('job_status') === $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </form>
    <div class="table-responsive">
    <table class="table table-striped" id="job-orders-table">
    <caption class="visually-hidden">Pending Job Orders</caption>
        <thead>
            <tr>
                <th>Type</th>
                <th>Description</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="job-orders-body">
            <?php $__empty_1 = true; $__currentLoopData = $jobOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($order->job_type); ?></td>
                <td><?php echo e(Str::limit($order->description, 50)); ?></td>
                <td><?php echo e(ucfirst($order->status)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="3" class="text-center">No records</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
    <?php echo e($jobOrders->appends(request()->except(['tickets_page','job_orders_page','requisitions_page','purchase_orders_page','incoming_docs_page','outgoing_docs_page','for_approval_docs_page']))->links()); ?>


    <h2 class="mt-5">Pending Requisitions</h2>
    <form method="GET" class="mb-2">
        <?php $__currentLoopData = request()->except(['requisition_status','tickets_page','job_orders_page','requisitions_page','purchase_orders_page','incoming_docs_page','outgoing_docs_page','for_approval_docs_page']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <input type="hidden" name="<?php echo e($name); ?>" value="<?php echo e($value); ?>">
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <select name="requisition_status" class="form-select w-auto d-inline" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            <?php $__currentLoopData = [\App\Models\Requisition::STATUS_PENDING_HEAD => 'Pending Head', \App\Models\Requisition::STATUS_APPROVED => 'Approved']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($val); ?>" <?php echo e(request('requisition_status') === $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </form>
    <div class="table-responsive">
    <table class="table table-striped" id="requisitions-table">
    <caption class="visually-hidden">Pending Requisitions</caption>
        <thead>
            <tr>
                <th>Purpose</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="requisitions-body">
            <?php $__empty_1 = true; $__currentLoopData = $requisitions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e(Str::limit($req->purpose, 50)); ?></td>
                <td><?php echo e(ucfirst(str_replace('_', ' ', $req->status))); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="2" class="text-center">No records</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
    <?php echo e($requisitions->appends(request()->except(['tickets_page','job_orders_page','requisitions_page','purchase_orders_page','incoming_docs_page','outgoing_docs_page','for_approval_docs_page']))->links()); ?>


    <h2 class="mt-5">Pending Purchase Orders</h2>
    <form method="GET" class="mb-2">
        <?php $__currentLoopData = request()->except(['po_status','tickets_page','job_orders_page','requisitions_page','purchase_orders_page','incoming_docs_page','outgoing_docs_page','for_approval_docs_page']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <input type="hidden" name="<?php echo e($name); ?>" value="<?php echo e($value); ?>">
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <select name="po_status" class="form-select w-auto d-inline" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            <?php $__currentLoopData = \App\Models\PurchaseOrder::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($status); ?>" <?php echo e(request('po_status') === $status ? 'selected' : ''); ?>>
                    <?php echo e(ucfirst(str_replace('_', ' ', $status))); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </form>
    <div class="table-responsive">
    <table class="table table-striped" id="purchase-orders-table">
    <caption class="visually-hidden">Pending Purchase Orders</caption>
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="purchase-orders-body">
            <?php $__empty_1 = true; $__currentLoopData = $purchaseOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $po): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($po->item); ?></td>
                <td><?php echo e($po->quantity); ?></td>
                <td><?php echo e(ucfirst(str_replace('_', ' ', $po->status))); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="3" class="text-center">No records</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
    <?php echo e($purchaseOrders->appends(request()->except(['tickets_page','job_orders_page','requisitions_page','purchase_orders_page','incoming_docs_page','outgoing_docs_page','for_approval_docs_page']))->links()); ?>


    <h2 class="mt-5">Incoming &amp; Outgoing Documents</h2>
    <div class="row">
        <div class="col-md-6">
            <h5>Incoming</h5>
            <div class="table-responsive">
            <table class="table table-striped" id="incoming-docs-table">
                <caption class="visually-hidden">Incoming Documents</caption>
                <thead>
                    <tr>
                        <th>Document</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody id="incoming-docs-body">
                    <?php $__empty_1 = true; $__currentLoopData = $incomingDocuments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($log->document->title); ?></td>
                        <td><?php echo e($log->user->name); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="2" class="text-center">No records</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
            <?php echo e($incomingDocuments->appends(request()->except(['tickets_page','job_orders_page','requisitions_page','purchase_orders_page','incoming_docs_page','outgoing_docs_page','for_approval_docs_page']))->links()); ?>

        </div>
        <div class="col-md-6">
            <h5>Outgoing</h5>
            <div class="table-responsive">
            <table class="table table-striped" id="outgoing-docs-table">
                <caption class="visually-hidden">Outgoing Documents</caption>
                <thead>
                    <tr>
                        <th>Document</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody id="outgoing-docs-body">
                    <?php $__empty_1 = true; $__currentLoopData = $outgoingDocuments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($log->document->title); ?></td>
                        <td><?php echo e($log->user->name); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="2" class="text-center">No records</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
            <?php echo e($outgoingDocuments->appends(request()->except(['tickets_page','job_orders_page','requisitions_page','purchase_orders_page','incoming_docs_page','outgoing_docs_page','for_approval_docs_page']))->links()); ?>

        </div>
    </div>

    <h2 class="mt-5">For Approval/Checking</h2>
    <caption class="visually-hidden">Documents for Approval</caption>
    <div class="table-responsive">
    <table class="table table-striped" id="for-approval-table">
        <thead>
            <tr>
                <th>Document</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody id="for-approval-body">
            <?php $__empty_1 = true; $__currentLoopData = $forApprovalDocuments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($log->document->title); ?></td>
                <td><?php echo e($log->user->name); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="2" class="text-center">No records</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
    <?php echo e($forApprovalDocuments->appends(request()->except(['tickets_page','job_orders_page','requisitions_page','purchase_orders_page','incoming_docs_page','outgoing_docs_page','for_approval_docs_page']))->links()); ?>

    <div id="dashboard-status" class="visually-hidden" aria-live="polite"></div>
</div>
<?php echo app('Illuminate\Foundation\Vite')('resources/js/dashboard.js'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/dashboard.blade.php ENDPATH**/ ?>