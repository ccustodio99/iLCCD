<?php

namespace App\Http\Controllers;

use App\Models\JobOrder;
use App\Models\InventoryItem;
use App\Models\Requisition;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JobOrderController extends Controller
{
    public function index()
    {
        $jobOrders = JobOrder::with(['requisitions', 'ticket', 'auditTrails.user'])
            ->where(function ($q) {
                $q->where('user_id', auth()->id())
                    ->orWhere('assigned_to_id', auth()->id());
            })
            ->paginate(10);
        return view('job_orders.index', compact('jobOrders')); 
    }

    public function create()
    {
        return view('job_orders.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'job_type' => 'required|string|max:255',
            'description' => 'required|string',
            'attachment' => 'nullable|file|max:2048',
        ]);
        $data['user_id'] = $request->user()->id;
        $data['status'] = JobOrder::STATUS_PENDING_HEAD;
        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')
                ->store('job_order_attachments', 'public');
        }
        JobOrder::create($data);
        return redirect()->route('job-orders.index');
    }

    public function edit(JobOrder $jobOrder)
    {
        if ($jobOrder->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        return view('job_orders.edit', compact('jobOrder'));
    }

    public function update(Request $request, JobOrder $jobOrder)
    {
        if ($jobOrder->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $data = $request->validate([
            'job_type' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string',
            'attachment' => 'nullable|file|max:2048',
        ]);
        if ($request->hasFile('attachment')) {
            if ($jobOrder->attachment_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($jobOrder->attachment_path);
            }
            $data['attachment_path'] = $request->file('attachment')
                ->store('job_order_attachments', 'public');
        }
        $jobOrder->update($data);
        if ($data['status'] === 'completed' && $jobOrder->completed_at === null) {
            $jobOrder->completed_at = now();
            $jobOrder->save();
        }
        return redirect()->route('job-orders.index');
    }

    public function complete(JobOrder $jobOrder)
    {
        if ($jobOrder->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }

        if ($jobOrder->status !== 'completed') {
            $jobOrder->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        }

        return redirect()->route('job-orders.index');
    }

    public function destroy(JobOrder $jobOrder)
    {
        if ($jobOrder->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $jobOrder->delete();
        return redirect()->route('job-orders.index');
    }

    /**
     * Request materials for this job order. If inventory stock exists,
     * it is deducted. Otherwise a linked requisition is created.
     */
    public function requestMaterials(Request $request, JobOrder $jobOrder)
    {
        if ($jobOrder->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }

        $data = $request->validate([
            'item' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'specification' => 'nullable|string',
            'purpose' => 'required|string',
        ]);

        $item = InventoryItem::where('name', $data['item'])->first();
        if ($item && $item->quantity >= $data['quantity']) {
            $item->decrement('quantity', $data['quantity']);
            InventoryTransaction::create([
                'inventory_item_id' => $item->id,
                'user_id' => $request->user()->id,
                'job_order_id' => $jobOrder->id,
                'action' => 'issue',
                'quantity' => $data['quantity'],
            ]);
        } else {
            $requisition = Requisition::create([
                'user_id' => $request->user()->id,
                'job_order_id' => $jobOrder->id,
                'department' => $request->user()->department,
                'purpose' => $data['purpose'],
                'status' => 'pending_head',
            ]);

            $requisition->items()->create([
                'item' => $data['item'],
                'quantity' => $data['quantity'],
                'specification' => $data['specification'] ?? null,
            ]);
        }

        return redirect()->route('job-orders.index');
    }

    public function downloadAttachment(JobOrder $jobOrder)
    {
        if ($jobOrder->attachment_path === null) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if ($jobOrder->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }

        return \Illuminate\Support\Facades\Storage::disk('public')
            ->download($jobOrder->attachment_path);
    }

    /** Show job orders awaiting the logged-in approver */
    public function approvals()
    {
        $role = auth()->user()->role;
        $statusMap = [
            'head' => JobOrder::STATUS_PENDING_HEAD,
            'president' => JobOrder::STATUS_PENDING_PRESIDENT,
            'finance' => JobOrder::STATUS_PENDING_FINANCE,
        ];

        $status = $statusMap[$role] ?? null;
        abort_if(!$status, Response::HTTP_FORBIDDEN, 'Access denied');

        $jobOrders = JobOrder::with('user')
            ->where('status', $status)
            ->paginate(10);

        return view('job_orders.approvals', compact('jobOrders'));
    }

    /** Approve the given job order and move to next stage */
    public function approve(JobOrder $jobOrder)
    {
        $this->authorizeApproval($jobOrder);

        $nextRole = null;
        if ($jobOrder->status === JobOrder::STATUS_PENDING_HEAD) {
            $jobOrder->status = JobOrder::STATUS_PENDING_PRESIDENT;
            $nextRole = 'president';
        } elseif ($jobOrder->status === JobOrder::STATUS_PENDING_PRESIDENT) {
            $jobOrder->status = JobOrder::STATUS_PENDING_FINANCE;
            $nextRole = 'finance';
        } elseif ($jobOrder->status === JobOrder::STATUS_PENDING_FINANCE) {
            $jobOrder->status = JobOrder::STATUS_APPROVED;
            $jobOrder->approved_at = now();
        }
        $jobOrder->save();

        // notify requester
        $jobOrder->user->notify(new \App\Notifications\JobOrderStatusNotification(
            "Your job order #{$jobOrder->id} status is now " . str_replace('_', ' ', $jobOrder->status)
        ));

        // notify next approver
        if ($nextRole) {
            $approver = \App\Models\User::where('role', $nextRole)->first();
            if ($approver) {
                $approver->notify(new \App\Notifications\JobOrderStatusNotification(
                    "Job order #{$jobOrder->id} requires your approval."
                ));
            }
        }

        return redirect()->route('job-orders.approvals');
    }

    /** Show approved job orders for assignment */
    public function assignments()
    {
        $jobOrders = JobOrder::where('status', JobOrder::STATUS_APPROVED)
            ->paginate(10);

        $staff = \App\Models\User::where('role', 'staff')->pluck('name', 'id');

        return view('job_orders.assign', compact('jobOrders', 'staff'));
    }

    /** Assign a job order to a staff */
    public function assign(Request $request, JobOrder $jobOrder)
    {
        $request->validate(['assigned_to_id' => 'required|exists:users,id']);

        $jobOrder->update([
            'assigned_to_id' => $request->assigned_to_id,
            'status' => JobOrder::STATUS_ASSIGNED,
        ]);

        // notify requester and assignee
        $jobOrder->user->notify(new \App\Notifications\JobOrderStatusNotification(
            "Your job order #{$jobOrder->id} has been assigned."
        ));

        if ($jobOrder->assignedTo) {
            $jobOrder->assignedTo->notify(new \App\Notifications\JobOrderStatusNotification(
                "Job order #{$jobOrder->id} has been assigned to you."
            ));
        }

        return redirect()->route('job-orders.assignments');
    }

    /** Show job orders assigned to the logged in user */
    public function assigned()
    {
        $jobOrders = JobOrder::where('assigned_to_id', auth()->id())
            ->whereIn('status', [JobOrder::STATUS_ASSIGNED, JobOrder::STATUS_IN_PROGRESS])
            ->paginate(10);

        return view('job_orders.assigned', compact('jobOrders'));
    }

    /** Mark the job order as started */
    public function start(Request $request, JobOrder $jobOrder)
    {
        abort_unless($jobOrder->assigned_to_id === $request->user()->id, Response::HTTP_FORBIDDEN);

        $data = $request->validate([
            'notes' => 'nullable|string',
        ]);

        $jobOrder->update([
            'status' => JobOrder::STATUS_IN_PROGRESS,
            'started_at' => now(),
            'start_notes' => $data['notes'] ?? null,
        ]);

        return redirect()->route('job-orders.assigned');
    }

    /** Mark the job order as completed */
    public function finish(Request $request, JobOrder $jobOrder)
    {
        abort_unless($jobOrder->assigned_to_id === $request->user()->id, Response::HTTP_FORBIDDEN);

        $data = $request->validate([
            'notes' => 'nullable|string',
        ]);

        $jobOrder->update([
            'status' => JobOrder::STATUS_COMPLETED,
            'completed_at' => now(),
            'completion_notes' => $data['notes'] ?? null,
        ]);

        return redirect()->route('job-orders.assigned');
    }

    private function authorizeApproval(JobOrder $jobOrder): void
    {
        $role = auth()->user()->role;
        $allowed = (
            ($role === 'head' && $jobOrder->status === JobOrder::STATUS_PENDING_HEAD) ||
            ($role === 'president' && $jobOrder->status === JobOrder::STATUS_PENDING_PRESIDENT) ||
            ($role === 'finance' && $jobOrder->status === JobOrder::STATUS_PENDING_FINANCE)
        );

        abort_unless($allowed, Response::HTTP_FORBIDDEN, 'Access denied');
    }
}
