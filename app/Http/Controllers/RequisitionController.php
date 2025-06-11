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
        $requisitions = Requisition::where('user_id', auth()->id())->paginate(10);
        return view('requisitions.index', compact('requisitions'));
    }

    public function create()
    {
        return view('requisitions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'item' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'specification' => 'nullable|string',
            'purpose' => 'required|string',
        ]);
        $data['user_id'] = $request->user()->id;
        $data['department'] = $request->user()->department;
        $data['status'] = 'pending_head';
        Requisition::create($data);
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
            'item' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'specification' => 'nullable|string',
            'purpose' => 'required|string',
            'status' => 'required|string',
        ]);
        $requisition->update($data);
        if ($data['status'] === 'approved' && $requisition->approved_at === null) {
            $requisition->approved_at = now();
            $requisition->approved_by_id = auth()->id();
            $requisition->save();

            $item = InventoryItem::where('name', $requisition->item)->first();
            if (!$item || $item->quantity < $requisition->quantity) {
                PurchaseOrder::create([
                    'user_id' => auth()->id(),
                    'requisition_id' => $requisition->id,
                    'inventory_item_id' => $item?->id,
                    'item' => $requisition->item,
                    'quantity' => $requisition->quantity,
                    'status' => 'draft',
                ]);
            } else {
                $item->decrement('quantity', $requisition->quantity);
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
