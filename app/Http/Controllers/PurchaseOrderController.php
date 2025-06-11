<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $orders = PurchaseOrder::where('user_id', auth()->id())
            ->with('auditTrails.user')
            ->paginate(10);
        return view('purchase_orders.index', compact('orders'));
    }

    public function create()
    {
        return view('purchase_orders.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'requisition_id' => 'required|integer|exists:requisitions,id',
            'inventory_item_id' => 'nullable|integer|exists:inventory_items,id',
            'item' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
        ]);

        $data['user_id'] = $request->user()->id;
        $data['status'] = 'draft';

        PurchaseOrder::create($data);

        return redirect()->route('purchase-orders.index');
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $purchaseOrder->load('auditTrails.user');
        return view('purchase_orders.edit', compact('purchaseOrder'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $data = $request->validate([
            'requisition_id' => 'required|integer|exists:requisitions,id',
            'inventory_item_id' => 'nullable|integer|exists:inventory_items,id',
            'item' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|string',
        ]);
        $purchaseOrder->update($data);

        if ($data['status'] === 'ordered' && $purchaseOrder->ordered_at === null) {
            $purchaseOrder->ordered_at = now();
            $purchaseOrder->save();
        }
        if ($data['status'] === 'received' && $purchaseOrder->received_at === null) {
            $purchaseOrder->received_at = now();
            if ($purchaseOrder->inventoryItem) {
                $purchaseOrder->inventoryItem->increment('quantity', $purchaseOrder->quantity);
            }
            $purchaseOrder->save();
        }

        return redirect()->route('purchase-orders.index');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $purchaseOrder->delete();
        return redirect()->route('purchase-orders.index');
    }
}
