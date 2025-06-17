<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use App\Models\InventoryItem;
use App\Models\PurchaseOrder;
use App\Models\InventoryTransaction;
use App\Models\JobOrder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class RequisitionController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);

        $query = Requisition::where('user_id', auth()->id());

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
            'quantity.*' => 'required|integer|min:1',
            'specification.*' => 'nullable|string',
            'purpose' => 'required|string',
            'remarks' => 'nullable|string',
            'attachment' => 'nullable|file|max:2048',
        ]);

        $requisitionData = [
            'user_id' => $request->user()->id,
            'department' => $request->user()->department,
            'purpose' => $data['purpose'],
            'remarks' => $data['remarks'] ?? null,
            'status' => Requisition::STATUS_PENDING_HEAD,
        ];

        if ($request->hasFile('attachment')) {
            $requisitionData['attachment_path'] = $request->file('attachment')
                ->store('requisition_attachments', 'public');
        }

        $requisition = Requisition::create($requisitionData);

        foreach ($data['item'] as $i => $name) {
            $requisition->items()->create([
                'item' => $name,
                'quantity' => $data['quantity'][$i] ?? 1,
                'specification' => $data['specification'][$i] ?? null,
            ]);
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
        if ($requisition->status === Requisition::STATUS_APPROVED) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        if ($requisition->status !== Requisition::STATUS_PENDING_HEAD && auth()->user()->role !== 'head') {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $data = $request->validate([
            'item.*' => 'required|string|max:255',
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
            if ($requisition->attachment_path) {
                Storage::disk('public')->delete($requisition->attachment_path);
            }
            $updateData['attachment_path'] = $request->file('attachment')
                ->store('requisition_attachments', 'public');
        }

        $requisition->update($updateData);

        $requisition->items()->delete();
        foreach ($data['item'] as $i => $name) {
            $requisition->items()->create([
                'item' => $name,
                'quantity' => $data['quantity'][$i] ?? 1,
                'specification' => $data['specification'][$i] ?? null,
            ]);
        }

        if ($data['status'] === Requisition::STATUS_APPROVED && $requisition->approved_at === null) {
            $requisition->approved_at = now();
            $requisition->approved_by_id = auth()->id();
            $requisition->save();

            foreach ($requisition->items as $reqItem) {
                $item = InventoryItem::where('name', $reqItem->item)->first();
                if (!$item || $item->quantity < $reqItem->quantity) {
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
        $role = auth()->user()->role;
        $statusMap = [
            'head' => Requisition::STATUS_PENDING_HEAD,
            'president' => Requisition::STATUS_PENDING_PRESIDENT,
            'finance' => Requisition::STATUS_PENDING_FINANCE,
        ];

        $status = $statusMap[$role] ?? null;
        abort_if(!$status, Response::HTTP_FORBIDDEN, 'Access denied');

        $requisitions = Requisition::with('user')
            ->where('status', $status)
            ->paginate($perPage)
            ->withQueryString();

        return view('requisitions.approvals', compact('requisitions'));
    }

    /** Approve the given requisition and move to next stage */
    public function approve(Requisition $requisition)
    {
        $this->authorizeApproval($requisition);

        $nextRole = null;
        if ($requisition->status === Requisition::STATUS_PENDING_HEAD) {
            $requisition->status = Requisition::STATUS_PENDING_PRESIDENT;
            $nextRole = 'president';
        } elseif ($requisition->status === Requisition::STATUS_PENDING_PRESIDENT) {
            $requisition->status = Requisition::STATUS_PENDING_FINANCE;
            $nextRole = 'finance';
        } elseif ($requisition->status === Requisition::STATUS_PENDING_FINANCE) {
            $requisition->status = Requisition::STATUS_APPROVED;
            $requisition->approved_at = now();
            $requisition->approved_by_id = auth()->id();
        }
        $requisition->save();

        // notify requester
        $requisition->user->notify(new \App\Notifications\RequisitionStatusNotification(
            "Your requisition #{$requisition->id} status is now " . str_replace('_', ' ', $requisition->status)
        ));

        // notify next approver
        if ($nextRole) {
            $approver = \App\Models\User::where('role', $nextRole)->first();
            if ($approver) {
                $approver->notify(new \App\Notifications\RequisitionStatusNotification(
                    "Requisition #{$requisition->id} requires your approval."
                ));
            }
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

        $requisition->update([
            'status' => Requisition::STATUS_PENDING_HEAD,
            'remarks' => $data['remarks'],
        ]);

        $requisition->user->notify(new \App\Notifications\RequisitionStatusNotification(
            "Requisition #{$requisition->id} was returned for revisions."
        ));

        return back();
    }

    private function authorizeApproval(Requisition $requisition): void
    {
        $role = auth()->user()->role;
        $allowed = (
            ($role === 'head' && $requisition->status === Requisition::STATUS_PENDING_HEAD) ||
            ($role === 'president' && $requisition->status === Requisition::STATUS_PENDING_PRESIDENT) ||
            ($role === 'finance' && $requisition->status === Requisition::STATUS_PENDING_FINANCE)
        );

        abort_unless($allowed, Response::HTTP_FORBIDDEN, 'Access denied');
    }
}
