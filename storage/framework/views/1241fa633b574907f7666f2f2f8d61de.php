<?php $__env->startSection('title', 'Tickets'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">My Tickets</h1>
    <?php echo $__env->make('components.per-page-selector', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#newTicketModal">New Ticket</button>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">Tickets</caption>
        <thead>
            <tr>
                <th>Category</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Due</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($ticket->ticketCategory->name); ?></td>
                <td><?php echo e($ticket->formatted_subject); ?></td>
                <td><?php echo e(ucfirst($ticket->status)); ?></td>
                <td><?php echo e(optional($ticket->due_at)->format('Y-m-d')); ?></td>
                <td>
                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#ticketModal<?php echo e($ticket->id); ?>">Details</button>
                    <button type="button" class="btn btn-sm btn-primary ms-1" data-bs-toggle="modal" data-bs-target="#editTicketModal<?php echo e($ticket->id); ?>">Edit</button>
                    <button type="button" class="btn btn-sm btn-secondary ms-1" data-bs-toggle="modal" data-bs-target="#convertJobOrderModal<?php echo e($ticket->id); ?>">Job Order</button>
                    <form action="<?php echo e(route('tickets.requisition', $ticket)); ?>" method="POST" class="d-inline ms-1">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Convert to Requisition?')">Requisition</button>
                    </form>
                    <form action="<?php echo e(route('tickets.destroy', $ticket)); ?>" method="POST" class="d-inline ms-1">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Archive this ticket?')">Archive</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    </div>

    <?php echo e($tickets->links()); ?>


    <div class="modal fade" id="newTicketModal" tabindex="-1" aria-labelledby="newTicketModalLabel" aria-hidden="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="newTicketModalLabel" class="modal-title">New Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo e(route('tickets.store')); ?>" method="POST" enctype="multipart/form-data" class="ticket-form">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <?php
                            $selectedModalSub = old('ticket_category_id');
                            $selectedModalCat = null;
                            foreach ($categories as $cat) {
                                if ($cat->children->contains('id', $selectedModalSub)) {
                                    $selectedModalCat = $cat->id;
                                    break;
                                }
                            }
                            $categoryData = $categories->mapWithKeys(function($cat) {
                                return [$cat->id => $cat->children->map(fn($c) => ['id' => $c->id, 'name' => $c->name])];
                            });
                        ?>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select category-select mb-2" data-categories='<?php echo json_encode($categoryData, 15, 512) ?>' required>
                                <option value="">Select Category</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat->id); ?>" <?php echo e((string)$selectedModalCat === (string)$cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <select name="ticket_category_id" class="form-select subcategory-select" data-selected="<?php echo e($selectedModalSub); ?>" required></select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" value="<?php echo e(old('subject')); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required><?php echo e(old('description')); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Attachment</label>
                            <input type="file" name="attachment" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Assign To</label>
                            <select name="assigned_to_id" class="form-select">
                                <option value="">Unassigned</option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($u->id); ?>" <?php echo e(old('assigned_to_id') == $u->id ? 'selected' : ''); ?>><?php echo e($u->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Watchers</label>
                            <select name="watchers[]" class="form-select" multiple>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($u->id); ?>" <?php echo e(collect(old('watchers'))->contains($u->id) ? 'selected' : ''); ?>><?php echo e($u->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <small class="text-muted">Hold Ctrl or Command to select multiple users</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_at" class="form-control" value="<?php echo e(old('due_at')); ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="ticketModal<?php echo e($ticket->id); ?>" tabindex="-1" aria-labelledby="ticketModalLabel<?php echo e($ticket->id); ?>" aria-hidden="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="ticketModalLabel<?php echo e($ticket->id); ?>" class="modal-title">Ticket Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Category:</strong> <?php echo e($ticket->ticketCategory->name); ?></p>
                    <p><strong>Subject:</strong> <?php echo e($ticket->formatted_subject); ?></p>
                    <p><strong>Description:</strong> <?php echo e($ticket->description); ?></p>
                    <?php if($ticket->attachment_path): ?>
                        <p><strong>Attachment:</strong> <a href="<?php echo e(route('tickets.attachment', $ticket)); ?>" target="_blank">Download</a></p>
                    <?php endif; ?>
                    <p><strong>Status:</strong> <?php echo e(ucfirst($ticket->status)); ?></p>
                    <p><strong>Assigned To:</strong> <?php echo e(optional($ticket->assignedTo)->name ?? 'Unassigned'); ?></p>
                    <p><strong>Watchers:</strong>
                        <?php $__currentLoopData = $ticket->watchers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="badge bg-secondary"><?php echo e($w->name); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </p>
                    <p><strong>Created:</strong> <?php echo e($ticket->created_at->format('Y-m-d H:i')); ?></p>
                    <p><strong>Updated:</strong> <?php echo e($ticket->updated_at->format('Y-m-d H:i')); ?></p>
                    <p><strong>Escalated:</strong> <?php echo e(optional($ticket->escalated_at)->format('Y-m-d H:i')); ?></p>
                    <p><strong>Resolved:</strong> <?php echo e(optional($ticket->resolved_at)->format('Y-m-d H:i')); ?></p>
                    <p><strong>Due:</strong> <?php echo e(optional($ticket->due_at)->format('Y-m-d H:i')); ?></p>

                    <?php if($ticket->jobOrder): ?>
                        <p><strong>Job Order ID:</strong>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#jobOrderModal<?php echo e($ticket->jobOrder->id); ?>">
                                <?php echo e($ticket->jobOrder->id); ?>

                            </a>
                        </p>
                    <?php endif; ?>

                    <?php if($ticket->requisitions->count()): ?>
                        <h6>Requisitions</h6>
                        <ul>
                            <?php $__currentLoopData = $ticket->requisitions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <a href="<?php echo e(route('requisitions.edit', $req)); ?>">#<?php echo e($req->id); ?></a>
                                    - <?php echo e(ucfirst(str_replace('_', ' ', $req->status))); ?>

                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php endif; ?>

                    <?php echo $__env->make('audit_trails._list', ['logs' => $ticket->auditTrails], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                    <?php if($ticket->comments->isNotEmpty()): ?>
                        <h6 class="mt-3">Comments</h6>
                        <ul class="list-group mb-3">
                            <?php $__currentLoopData = $ticket->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <span><?php echo e($comment->created_at->format('Y-m-d H:i')); ?></span>
                                        <span><?php echo e($comment->user->name); ?></span>
                                    </div>
                                    <p class="mb-0 mt-1"><?php echo e($comment->comment); ?></p>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php endif; ?>

                    <?php if(auth()->id() === $ticket->user_id || auth()->id() === $ticket->assigned_to_id || $ticket->watchers->contains(auth()->id())): ?>
                        <form action="<?php echo e(route('tickets.comment', $ticket)); ?>" method="POST" class="mb-3">
                            <?php echo csrf_field(); ?>
                            <textarea name="comment" class="form-control mb-2" rows="2" required></textarea>
                            <button type="submit" class="btn btn-primary btn-sm">Add Comment</button>
                        </form>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editTicketModal<?php echo e($ticket->id); ?>" tabindex="-1" aria-labelledby="editTicketModalLabel<?php echo e($ticket->id); ?>" aria-hidden="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="editTicketModalLabel<?php echo e($ticket->id); ?>" class="modal-title">Edit Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo e(route('tickets.update', $ticket)); ?>" method="POST" enctype="multipart/form-data" class="ticket-form">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-body">
                        <?php
                            $editSub = old('ticket_category_id', $ticket->ticket_category_id);
                            $editCat = null;
                            foreach ($categories as $cat) {
                                if ($cat->children->contains('id', $editSub)) {
                                    $editCat = $cat->id;
                                    break;
                                }
                            }
                            $categoryData = $categories->mapWithKeys(function($cat) {
                                return [$cat->id => $cat->children->map(fn($c) => ['id' => $c->id, 'name' => $c->name])];
                            });
                        ?>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select category-select mb-2" data-categories='<?php echo json_encode($categoryData, 15, 512) ?>' required>
                                <option value="">Select Category</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat->id); ?>" <?php echo e((string)$editCat === (string)$cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <select name="ticket_category_id" class="form-select subcategory-select" data-selected="<?php echo e($editSub); ?>" required></select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" value="<?php echo e(old('subject', $ticket->subject)); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required><?php echo e(old('description', $ticket->description)); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Attachment</label>
                            <input type="file" name="attachment" class="form-control">
                            <?php if($ticket->attachment_path): ?>
                                <small class="text-muted">Current: <a href="<?php echo e(route('tickets.attachment', $ticket)); ?>" target="_blank">Download</a></small>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Assign To</label>
                            <select name="assigned_to_id" class="form-select">
                                <option value="">Unassigned</option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($u->id); ?>" <?php echo e(old('assigned_to_id', $ticket->assigned_to_id) == $u->id ? 'selected' : ''); ?>><?php echo e($u->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Watchers</label>
                            <select name="watchers[]" class="form-select" multiple>
                                <?php ($selected = old('watchers', $ticket->watchers->pluck('id')->toArray())); ?>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($u->id); ?>" <?php echo e(in_array($u->id, $selected) ? 'selected' : ''); ?>><?php echo e($u->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <small class="text-muted">Hold Ctrl or Command to select multiple users</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <?php ($statuses = ['open' => 'Open', 'escalated' => 'Escalated', 'converted' => 'Converted', 'closed' => 'Closed']); ?>
                                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>" <?php echo e(old('status', $ticket->status) === $value ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_at" class="form-control" value="<?php echo e(old('due_at', optional($ticket->due_at)->format('Y-m-d'))); ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="convertJobOrderModal<?php echo e($ticket->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Job Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo e(route('tickets.convert', $ticket)); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type_parent" id="type_parent_convert_<?php echo e($ticket->id); ?>" class="form-select" required>
                                <option value="">Select Type</option>
                                <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($type->id); ?>" <?php echo e(old('type_parent') == $type->id ? 'selected' : ''); ?>><?php echo e($type->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sub Type</label>
                            <select name="job_type" id="job_type_convert_<?php echo e($ticket->id); ?>" class="form-select" required disabled>
                                <option value="">Select Sub Type</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required><?php echo e('Ticket #' . $ticket->id . ' - ' . $ticket->subject . "\n" . $ticket->description); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Attachment</label>
                            <input type="file" name="attachment" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const parent<?php echo e($ticket->id); ?> = document.getElementById('type_parent_convert_<?php echo e($ticket->id); ?>');
                    const child<?php echo e($ticket->id); ?> = document.getElementById('job_type_convert_<?php echo e($ticket->id); ?>');

                    function loadChildren<?php echo e($ticket->id); ?>(id, selected) {
                        child<?php echo e($ticket->id); ?>.innerHTML = '<option value="">Select Sub Type</option>';
                        if (!id) {
                            child<?php echo e($ticket->id); ?>.disabled = true;
                            return;
                        }
                        child<?php echo e($ticket->id); ?>.disabled = false;
                        fetch(`/job-order-types/${id}/children`)
                            .then(r => r.json())
                            .then(data => {
                                data.forEach(c => {
                                    const opt = document.createElement('option');
                                    opt.value = c.name;
                                    opt.textContent = c.name;
                                    if (selected === c.name) opt.selected = true;
                                    child<?php echo e($ticket->id); ?>.appendChild(opt);
                                });
                            });
                    }

                    parent<?php echo e($ticket->id); ?>.addEventListener('change', () => loadChildren<?php echo e($ticket->id); ?>(parent<?php echo e($ticket->id); ?>.value));
                    loadChildren<?php echo e($ticket->id); ?>(parent<?php echo e($ticket->id); ?>.value, '<?php echo e(old('job_type')); ?>');
                });
                </script>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php echo $__env->make('partials.category-dropdown-script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/tickets/index.blade.php ENDPATH**/ ?>