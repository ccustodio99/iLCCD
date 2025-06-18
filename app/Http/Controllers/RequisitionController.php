<?php

namespace App\Http\Controllers;

use App\Models\ApprovalProcess;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use App\Models\JobOrder;
use App\Models\PurchaseOrder;
use App\Models\Requisition;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class RequisitionController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);

        $user = $request->user();
        $query = Requisition::where(function ($q) use ($user) {
            $q->where('user_id', $user->id);
            if ($user->role === 'head') {
                $q->orWhere('department', $user->department);
            }
        });

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('ticket_id')) {
            $query->where('ticket_id', $request->input('ticket_id'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('purpose', 'like', "%{$search}%")
                    ->orWhere('remarks', 'like', "%{$search}%")
                    ->orWhereHas('items', function ($iq) use ($search) {
                        $iq->where('item', 'like', "%{$search}%")
                            ->orWhere('specification', 'like', "%{$search}%");
                    });
            });
        }

        $requisitions = $query
            ->with(['items', 'auditTrails.user'])
            ->paginate($perPage)
            ->withQueryString();

        $statuses = Requisition::select('status')->distinct()->pluck('status');

        return view('requisitions.index', compact('requisitions', 'statuses'));
    }

    public function create()
    {
        return view('requisitions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'item.*' => 'required|string|max:255',
            'sku.*' => 'nullable|string|max:255',
            'quantity.*' => 'required|integer|min:1',
            'specification.*' => 'nullable|string',
            'purpose' => 'required|string',
            'remarks' => 'nullable|string',
            'attachment' => 'nullable|file|max:2048',
        ]);

        $process = ApprovalProcess::where('module', 'requisitions')
            ->where('department', $request->user()->department)
            ->with('stages')
            ->first();

        $firstStage = $process?->stages->sortBy('position')->first();

        $requisitionData = [
            'user_id' => $request->user()->id,
            'department' => $request->user()->department,
            'purpose' => $data['purpose'],
            'remarks' => $data['remarks'] ?? null,
            'status' => $firstStage->name ?? Requisition::STATUS_PENDING_HEAD,
        ];


        if ($request->hasFile('attachment')) {
            try {
                $requisitionData['attachment_path'] = $request->file('attachment')
                    ->store('requisition_attachments', 'public');
            } catch (\Throwable $e) {
                Log::error('Failed to store requisition attachment: '.$e->getMessage());
            }
        }


        try {
            if ($request->hasFile('attachment')) {
                try {
                    $requisitionData['attachment_path'] = $request->file('attachment')
                        ->store('requisition_attachments', 'public');
                } catch (\Throwable $e) {
                    DB::rollBack();


                    return back()
                        ->withErrors(['attachment' => 'Failed to upload attachment.'])
                        ->withInput();
                }
            }

            $requisition = Requisition::create($requisitionData);

            foreach ($data['item'] as $i => $name) {
                $requisition->items()->create([
                    'item' => $name,
                    'quantity' => $data['quantity'][$i] ?? 1,
                    'specification' => $data['specification'][$i] ?? null,
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            if (! empty($requisitionData['attachment_path'] ?? null)) {
                Storage::disk('public')->delete($requisitionData['attachment_path']);
            }

            DB::rollBack();

            return back()
                ->withErrors(['error' => 'Failed to create requisition.'])
                ->withInput();
        }

        return redirect()->route('requisitions.index');
    }

    public function edit(Requisition $requisition)
    {
        if ($requisition->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $requisition->load(['items', 'auditTrails.user']);

        return view('requisitions.edit', compact('requisition'));
    }

    public function update(Request $request, Requisition $requisition)
    {
        if ($requisition->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        if (auth()->user()->department !== $requisition->department) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        if ($requisition->status === Requisition::STATUS_APPROVED) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        if ($requisition->status !== Requisition::STATUS_PENDING_HEAD && auth()->user()->role !== 'head') {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $data = $request->validate([
            'item.*' => 'required|string|max:255',
            'sku.*' => 'nullable|string|max:255',
            'quantity.*' => 'required|integer|min:1',
            'specification.*' => 'nullable|string',
            'purpose' => 'required|string',
            'remarks' => 'nullable|string',
            'status' => ['required', 'string', Rule::in(Requisition::STATUSES)],
            'attachment' => 'nullable|file|max:2048',
        ]);

        $updateData = [
            'purpose' => $data['purpose'],
            'remarks' => $data['remarks'] ?? null,
            'status' => $data['status'],
        ];

        if ($request->hasFile('attachment')) {
            $oldPath = $requisition->attachment_path;
            try {
                if ($oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
                $updateData['attachment_path'] = $request->file('attachment')
                    ->store('requisition_attachments', 'public');
            } catch (\Throwable $e) {
                Log::error('Failed to replace requisition attachment: '.$e->getMessage());
                $updateData['attachment_path'] = $oldPath;
            }
        }

        $requisition->update($updateData);

        $requisition->items()->delete();
        foreach ($data['item'] as $i => $name) {
            $requisition->items()->create([
                'item' => $name,
                'sku' => $data['sku'][$i] ?? null,
                'quantity' => $data['quantity'][$i] ?? 1,
                'specification' => $data['specification'][$i] ?? null,
            ]);
        }

        if ($data['status'] === Requisition::STATUS_APPROVED && $requisition->approved_at === null) {

            DB::transaction(function () use ($requisition) {
                $requisition->approved_at = now();
                $requisition->approved_by_id = auth()->id();
                $requisition->save();

                foreach ($requisition->items as $reqItem) {
                    $item = InventoryItem::where('name', $reqItem->item)->first();

                    if (! $item || $item->quantity < $reqItem->quantity) {
                        PurchaseOrder::create([
                            'user_id' => auth()->id(),
                            'requisition_id' => $requisition->id,
                            'inventory_item_id' => $item?->id,
                            'item' => $reqItem->item,
                            'quantity' => $reqItem->quantity,
                            'status' => PurchaseOrder::STATUS_DRAFT,
                        ]);
                    } else {
                        $item->decrement('quantity', $reqItem->quantity);
                        InventoryTransaction::create([
                            'inventory_item_id' => $item->id,
                            'user_id' => auth()->id(),
                            'requisition_id' => $requisition->id,
                            'action' => 'issue',
                            'quantity' => $reqItem->quantity,
                            'purpose' => $requisition->purpose,
                        ]);
                    }
                }

                if ($requisition->job_order_id) {
                    $jobOrder = $requisition->jobOrder;
                    if ($jobOrder && $jobOrder->status !== JobOrder::STATUS_APPROVED) {
                        $jobOrder->update([
                            'status' => JobOrder::STATUS_APPROVED,
                            'approved_at' => now(),
                        ]);
                    }
                }
            });
        }

        return redirect()->route('requisitions.index');
    }

    public function destroy(Requisition $requisition)
    {
        if ($requisition->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        if ($requisition->status !== Requisition::STATUS_PENDING_HEAD) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $requisition->delete();

        return redirect()->route('requisitions.index');
    }

    public function downloadAttachment(Requisition $requisition)
    {
        if ($requisition->attachment_path === null) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if ($requisition->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }

        return Storage::disk('public')->download($requisition->attachment_path);
    }

    /** Show requisitions awaiting the logged-in approver */
    public function approvals(Request $request)
    {
        $perPage = $this->getPerPage($request);
        $user = auth()->user();

        abort_if($user->role !== 'head', Response::HTTP_FORBIDDEN, 'Access denied');

        $processes = ApprovalProcess::where('module', 'requisitions')
            ->with('stages')
            ->get();

        $pairs = [];

        foreach ($processes as $process) {
            foreach ($process->stages as $stage) {
                $allowed = false;

                if ($stage->assigned_user_id) {
                    $allowed = $stage->assigned_user_id === $user->id;
                } elseif ($stage->name === Requisition::STATUS_PENDING_HEAD && $user->department === $process->department) {
                    $allowed = true;
                } elseif ($stage->name === Requisition::STATUS_PENDING_PRESIDENT && $user->department === 'President Department') {
                    $allowed = true;
                } elseif ($stage->name === Requisition::STATUS_PENDING_FINANCE && $user->department === 'Finance Office') {
                    $allowed = true;
                }

                if ($allowed) {
                    $pairs[] = [$stage->name, $process->department];
                }
            }
        }

        if (empty($pairs)) {
            $requisitions = Requisition::whereRaw('1=0')
                ->paginate($perPage)
                ->withQueryString();
        } else {
            $requisitions = Requisition::with('user')
                ->where(function ($query) use ($pairs) {
                    foreach ($pairs as $pair) {
                        [$status, $dept] = $pair;
                        $query->orWhere(function ($q) use ($status, $dept) {
                            $q->where('status', $status);

                            if ($status === Requisition::STATUS_PENDING_HEAD) {
                                $q->where('department', $dept);
                            }
                        });
                    }
                })
                ->paginate($perPage)
                ->withQueryString();
        }

        return view('requisitions.approvals', compact('requisitions'));
    }

    /** Approve the given requisition and move to next stage */
    public function approve(Requisition $requisition)
    {
        $this->authorizeApproval($requisition);

        $process = ApprovalProcess::where('module', 'requisitions')
            ->where('department', $requisition->department)
            ->with('stages')
            ->first();
        $stages = $process?->stages->sortBy('position')->values();

        $currentIndex = $stages->search(fn ($s) => $s->name === $requisition->status);

        if ($stages->isEmpty() || $currentIndex === false) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Approval process misconfigured.');
        }

        $nextStage = $stages->get($currentIndex + 1);
        $nextApprover = null;

        if ($nextStage) {
            $requisition->status = $nextStage->name;
            $nextApprover = $nextStage->assigned_user_id
                ? User::find($nextStage->assigned_user_id)
                : null;
            if (! $nextApprover) {
                if ($nextStage->name === Requisition::STATUS_PENDING_HEAD) {
                    $nextApprover = User::where([
                        'role' => 'head',
                        'department' => $requisition->department,
                    ])->first();
                } elseif ($nextStage->name === Requisition::STATUS_PENDING_PRESIDENT) {
                    $nextApprover = User::where([
                        'role' => 'head',
                        'department' => 'President Department',
                    ])->first();
                } elseif ($nextStage->name === Requisition::STATUS_PENDING_FINANCE) {
                    $nextApprover = User::where([
                        'role' => 'head',
                        'department' => 'Finance Office',
                    ])->first();
                }
            }
        } else {
            $requisition->status = Requisition::STATUS_APPROVED;
            $requisition->approved_at = now();
            $requisition->approved_by_id = auth()->id();
        }

        $requisition->save();

        // notify requester
        $requisition->user->notify(new \App\Notifications\RequisitionStatusNotification(
            "Your requisition #{$requisition->id} status is now ".str_replace('_', ' ', $requisition->status)
        ));

        // notify next approver
        if ($nextApprover) {
            $nextApprover->notify(new \App\Notifications\RequisitionStatusNotification(
                "Requisition #{$requisition->id} requires your approval."
            ));
        }

        return redirect()->route('requisitions.approvals');
    }

    /**
     * Return the requisition to pending_head for revisions.
     */
    public function returnToPending(Request $request, Requisition $requisition)
    {
        abort_unless(auth()->user()->role === 'head', Response::HTTP_FORBIDDEN);

        $data = $request->validate(['remarks' => 'required|string']);

        $process = ApprovalProcess::where('module', 'requisitions')
            ->where('department', $requisition->department)
            ->with('stages')
            ->first();
        $firstStage = $process?->stages->sortBy('position')->first();

        $requisition->update([
            'status' => $firstStage->name ?? Requisition::STATUS_PENDING_HEAD,
            'remarks' => $data['remarks'],
        ]);

        $requisition->user->notify(new \App\Notifications\RequisitionStatusNotification(
            "Requisition #{$requisition->id} was returned for revisions."
        ));

        return back();
    }

    private function authorizeApproval(Requisition $requisition): void
    {
        $user = auth()->user();
        $allowed = false;

        $process = ApprovalProcess::where('module', 'requisitions')
            ->where('department', $requisition->department)
            ->with('stages')
            ->first();
        $stage = $process?->stages->firstWhere('name', $requisition->status);

        if ($user->role === 'head' && $stage) {
            if ($stage->assigned_user_id) {
                $allowed = $user->id === $stage->assigned_user_id;
            } elseif ($stage->name === Requisition::STATUS_PENDING_HEAD && $user->department === $requisition->department) {
                $allowed = true;
            } elseif ($stage->name === Requisition::STATUS_PENDING_PRESIDENT && $user->department === 'President Department') {
                $allowed = true;
            } elseif ($stage->name === Requisition::STATUS_PENDING_FINANCE && $user->department === 'Finance Office') {
                $allowed = true;
            }
        }

        abort_unless($allowed, Response::HTTP_FORBIDDEN, 'Access denied');
    }
}
