<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use App\Models\InventoryItem;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequisitionController extends Controller
{
    public function index()
    {
        $requisitions = Requisition::where('user_id', auth()->id())
            ->with('items')
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
        ]);

        $requisition = Requisition::create([
            'user_id' => $request->user()->id,
            'department' => $request->user()->department,
            'purpose' => $data['purpose'],
            'status' => 'pending_head',
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
            'status' => 'required|string',
        ]);

        $requisition->update([
            'purpose' => $data['purpose'],
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

        if ($data['status'] === 'approved' && $requisition->approved_at === null) {
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
}
