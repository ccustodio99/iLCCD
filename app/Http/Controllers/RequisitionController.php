<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use App\Models\InventoryItem;
use App\Models\PurchaseOrder;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequisitionController extends Controller
{
    public function index()
    {
        $requisitions = Requisition::where('user_id', auth()->id())
            ->with(['items', 'auditTrails.user'])
            ->paginate(10);
        return view('requisitions.index', compact('requisitions'));
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
        ]);

        $requisition = Requisition::create([
            'user_id' => $request->user()->id,
            'department' => $request->user()->department,
            'purpose' => $data['purpose'],
            'remarks' => $data['remarks'] ?? null,
            'status' => Requisition::STATUS_PENDING_HEAD,
        ]);

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
        $data = $request->validate([
            'item.*' => 'required|string|max:255',
            'quantity.*' => 'required|integer|min:1',
            'specification.*' => 'nullable|string',
            'purpose' => 'required|string',
            'remarks' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $requisition->update([
            'purpose' => $data['purpose'],
            'remarks' => $data['remarks'] ?? null,
            'status' => $data['status'],
        ]);

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
                        'status' => 'draft',
                    ]);
                } else {
                    $item->decrement('quantity', $reqItem->quantity);
                    InventoryTransaction::create([
                        'inventory_item_id' => $item->id,
                        'user_id' => auth()->id(),
                        'requisition_id' => $requisition->id,
                        'action' => 'issue',
                        'quantity' => $reqItem->quantity,
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
        $requisition->delete();
        return redirect()->route('requisitions.index');
    }

    /** Show requisitions awaiting the logged-in approver */
    public function approvals()
    {
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
            ->paginate(10);

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
