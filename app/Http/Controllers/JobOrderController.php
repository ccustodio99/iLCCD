<?php

namespace App\Http\Controllers;

use App\Models\ApprovalProcess;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use App\Models\JobOrder;
use App\Models\JobOrderType;
use App\Models\Requisition;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class JobOrderController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);

        $user = $request->user();
        $query = JobOrder::with(['requisitions', 'ticket', 'auditTrails.user'])
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('assigned_to_id', $user->id);

                if ($user->role === 'head') {
                    $q->orWhereHas('user', function ($uq) use ($user) {
                        $uq->where('department', $user->department);
                    });
                }
            });

        if (! $request->boolean('closed')) {
            $query->where('status', '!=', JobOrder::STATUS_CLOSED);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('type_parent')) {
            $typeIds = JobOrderType::where('parent_id', $request->input('type_parent'))
                ->pluck('name');
            $query->whereIn('job_type', $typeIds);
        }

        if ($request->filled('job_type')) {
            $query->where('job_type', $request->input('job_type'));
        }

        if ($request->filled('assigned_to_id')) {
            $query->where('assigned_to_id', $request->input('assigned_to_id'));
        }

        if ($request->filled('search')) {
            $query->where('description', 'like', '%'.$request->input('search').'%');
        }

        $jobOrders = $query
            ->paginate($perPage)
            ->withQueryString();

        $types = JobOrderType::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $users = \App\Models\User::orderBy('name')->get();
        $statuses = JobOrder::select('status')->distinct()->pluck('status');

        return view('job_orders.index', compact('jobOrders', 'types', 'users', 'statuses'));
    }

    public function create()
    {
        $types = JobOrderType::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('name')
            ->with('children')
            ->get();

        return view('job_orders.create', compact('types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type_parent' => ['required', 'exists:job_order_types,id'],
            'job_type' => [
                'required',
                'string',
                'max:255',
                Rule::exists('job_order_types', 'name')->where(function ($q) use ($request) {
                    $q->where('parent_id', $request->type_parent)
                        ->where('is_active', true);
                }),
            ],
            'description' => 'required|string',
            'attachment' => 'nullable|file|max:2048',
        ]);
        $process = ApprovalProcess::where('module', 'job_orders')
            ->where('department', $request->user()->department)
            ->with('stages')
            ->first();
        $firstStage = $process?->stages->sortBy('position')->first();

        $data['user_id'] = $request->user()->id;
        $data['status'] = $firstStage->name ?? JobOrder::STATUS_PENDING_HEAD;
        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')
                ->store('job_order_attachments', 'public');
        }
        unset($data['type_parent']);
        JobOrder::create($data);

        return redirect()->route('job-orders.index');
    }

    public function edit(JobOrder $jobOrder)
    {
        if ($jobOrder->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        if ($jobOrder->status !== JobOrder::STATUS_PENDING_HEAD && auth()->user()->role !== 'head') {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $types = JobOrderType::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('name')
            ->with('children')
            ->get();

        $child = JobOrderType::where('name', $jobOrder->job_type)->first();
        $parentId = $child?->parent_id;

        return view('job_orders.edit', compact('jobOrder', 'types', 'parentId'));
    }

    public function update(Request $request, JobOrder $jobOrder)
    {
        if ($jobOrder->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        if ($jobOrder->status !== JobOrder::STATUS_PENDING_HEAD && auth()->user()->role !== 'head') {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $data = $request->validate([
            'type_parent' => ['required', 'exists:job_order_types,id'],
            'job_type' => [
                'required',
                'string',
                'max:255',
                Rule::exists('job_order_types', 'name')->where(function ($q) use ($request) {
                    $q->where('parent_id', $request->type_parent)
                        ->where('is_active', true);
                }),
            ],
            'description' => 'required|string',
            'attachment' => 'nullable|file|max:2048',
        ]);
        if ($request->hasFile('attachment')) {
            if ($jobOrder->attachment_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($jobOrder->attachment_path);
            }
            $data['attachment_path'] = $request->file('attachment')
                ->store('job_order_attachments', 'public');
        }
        unset($data['type_parent']);
        $jobOrder->update($data);

        return redirect()->route('job-orders.index');
    }

    public function complete(JobOrder $jobOrder)
    {
        if ($jobOrder->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }

        if ($jobOrder->status !== JobOrder::STATUS_COMPLETED) {
            $jobOrder->update([
                'status' => JobOrder::STATUS_COMPLETED,
                'completed_at' => now(),
            ]);
        }

        return redirect()->route('job-orders.index');
    }

    public function close(JobOrder $jobOrder)
    {
        if ($jobOrder->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }

        if ($jobOrder->status === JobOrder::STATUS_COMPLETED) {
            $jobOrder->update([
                'status' => JobOrder::STATUS_CLOSED,
                'closed_at' => now(),
            ]);
        }

        return redirect()->route('job-orders.index');
    }

    public function destroy(JobOrder $jobOrder)
    {
        if ($jobOrder->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        if ($jobOrder->status !== JobOrder::STATUS_PENDING_HEAD) {
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
                'purpose' => $data['purpose'],
            ]);
        } else {
            $requisition = Requisition::create([
                'user_id' => $request->user()->id,
                'job_order_id' => $jobOrder->id,
                'department' => $request->user()->department,
                'purpose' => $data['purpose'],
                'status' => Requisition::STATUS_PENDING_HEAD,
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
    public function approvals(Request $request)
    {
        $perPage = $this->getPerPage($request);
        $user = auth()->user();

        abort_if($user->role !== 'head', Response::HTTP_FORBIDDEN, 'Access denied');

        $processes = ApprovalProcess::where('module', 'job_orders')
            ->with('stages')
            ->get();

        $pairs = [];

        foreach ($processes as $process) {
            foreach ($process->stages as $stage) {
                $allowed = false;

                if ($stage->assigned_user_id) {
                    $allowed = $stage->assigned_user_id === $user->id;
                } elseif ($stage->name === JobOrder::STATUS_PENDING_HEAD && $user->department === $process->department) {
                    $allowed = true;
                } elseif ($stage->name === JobOrder::STATUS_PENDING_PRESIDENT && $user->department === 'President Department') {
                    $allowed = true;
                } elseif ($stage->name === JobOrder::STATUS_PENDING_FINANCE && $user->department === 'Finance Office') {
                    $allowed = true;
                }

                if ($allowed) {
                    $pairs[] = [$stage->name, $process->department];
                }
            }
        }

        if (empty($pairs)) {
            $jobOrders = JobOrder::whereRaw('1=0')
                ->paginate($perPage)
                ->withQueryString();
        } else {
            $jobOrders = JobOrder::with('user')
                ->where(function ($query) use ($pairs) {
                    foreach ($pairs as $pair) {
                        [$status, $dept] = $pair;
                        $query->orWhere(function ($q) use ($status, $dept) {
                            $q->where('status', $status);

                            if ($status === JobOrder::STATUS_PENDING_HEAD) {
                                $q->whereHas('user', function ($q2) use ($dept) {
                                    $q2->where('department', $dept);
                                });
                            }
                        });
                    }
                })
                ->paginate($perPage)
                ->withQueryString();
        }

        return view('job_orders.approvals', compact('jobOrders'));
    }

    /** Approve the given job order and move to next stage */
    public function approve(JobOrder $jobOrder)
    {
        $this->authorizeApproval($jobOrder);

        $process = ApprovalProcess::where('module', 'job_orders')
            ->where('department', $jobOrder->user->department)
            ->with('stages')
            ->first();
        $stages = $process?->stages->sortBy('position')->values();

        $currentIndex = $stages->search(fn ($s) => $s->name === $jobOrder->status);

        if ($stages->isEmpty() || $currentIndex === false) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Approval process misconfigured.');
        }

        $nextStage = $stages->get($currentIndex + 1);
        $nextApprover = null;

        if ($nextStage) {
            $jobOrder->status = $nextStage->name;
            $nextApprover = $nextStage->assigned_user_id
                ? User::find($nextStage->assigned_user_id)
                : null;
            if (! $nextApprover) {
                if ($nextStage->name === JobOrder::STATUS_PENDING_HEAD) {
                    $nextApprover = User::where([
                        'role' => 'head',
                        'department' => $jobOrder->user->department,
                    ])->first();
                } elseif ($nextStage->name === JobOrder::STATUS_PENDING_PRESIDENT) {
                    $nextApprover = User::where([
                        'role' => 'head',
                        'department' => 'President Department',
                    ])->first();
                } elseif ($nextStage->name === JobOrder::STATUS_PENDING_FINANCE) {
                    $nextApprover = User::where([
                        'role' => 'head',
                        'department' => 'Finance Office',
                    ])->first();
                }
            }
        } else {
            $jobOrder->status = JobOrder::STATUS_APPROVED;
            $jobOrder->approved_at = now();
        }

        $jobOrder->save();

        // notify requester
        $jobOrder->user->notify(new \App\Notifications\JobOrderStatusNotification(
            "Your job order #{$jobOrder->id} status is now ".str_replace('_', ' ', $jobOrder->status)
        ));

        // notify next approver
        if ($nextApprover) {
            $nextApprover->notify(new \App\Notifications\JobOrderStatusNotification(
                "Job order #{$jobOrder->id} requires your approval."
            ));
        }

        return redirect()->route('job-orders.index');
    }

    /**
     * Return the job order to pending_head for revisions.
     */
    public function returnToPending(Request $request, JobOrder $jobOrder)
    {
        abort_unless(auth()->user()->role === 'head', Response::HTTP_FORBIDDEN);

        $request->validate(['remarks' => 'required|string']);

        $process = ApprovalProcess::where('module', 'job_orders')
            ->where('department', $jobOrder->user->department)
            ->with('stages')
            ->first();
        $firstStage = $process?->stages->sortBy('position')->first();

        $jobOrder->update([
            'status' => $firstStage->name ?? JobOrder::STATUS_PENDING_HEAD,
        ]);

        $jobOrder->user->notify(new \App\Notifications\JobOrderStatusNotification(
            "Job order #{$jobOrder->id} was returned for revisions."
        ));

        return back();
    }

    /** Show approved job orders for assignment */
    public function assignments(Request $request)
    {
        $perPage = $this->getPerPage($request);

        $jobOrders = JobOrder::where('status', JobOrder::STATUS_APPROVED)
            ->paginate($perPage)
            ->withQueryString();

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
    public function assigned(Request $request)
    {
        $perPage = $this->getPerPage($request);

        $jobOrders = JobOrder::where('assigned_to_id', auth()->id())
            ->whereIn('status', [JobOrder::STATUS_ASSIGNED, JobOrder::STATUS_IN_PROGRESS])
            ->paginate($perPage)
            ->withQueryString();

        return view('job_orders.assigned', compact('jobOrders'));
    }

    /** Mark the job order as started */
    public function start(Request $request, JobOrder $jobOrder)
    {
        abort_unless($jobOrder->assigned_to_id === $request->user()->id, Response::HTTP_FORBIDDEN);
        abort_if($jobOrder->status !== JobOrder::STATUS_ASSIGNED, Response::HTTP_FORBIDDEN);

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
        abort_if($jobOrder->status !== JobOrder::STATUS_IN_PROGRESS, Response::HTTP_FORBIDDEN);

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
        $user = auth()->user();
        $allowed = false;

        $process = ApprovalProcess::where('module', 'job_orders')
            ->where('department', $jobOrder->user->department)
            ->with('stages')
            ->first();
        $stage = $process?->stages->firstWhere('name', $jobOrder->status);

        if ($user->role === 'head' && $stage) {
            if ($stage->assigned_user_id) {
                $allowed = $user->id === $stage->assigned_user_id;
            } elseif ($stage->name === JobOrder::STATUS_PENDING_HEAD && $user->department === $jobOrder->user->department) {
                $allowed = true;
            } elseif ($stage->name === JobOrder::STATUS_PENDING_PRESIDENT && $user->department === 'President Department') {
                $allowed = true;
            } elseif ($stage->name === JobOrder::STATUS_PENDING_FINANCE && $user->department === 'Finance Office') {
                $allowed = true;
            }
        }

        abort_unless($allowed, Response::HTTP_FORBIDDEN, 'Access denied');
    }
}
