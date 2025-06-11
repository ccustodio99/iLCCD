<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InventoryItemController extends Controller
{
    public function index()
    {
        $items = InventoryItem::where('user_id', auth()->id())
            ->with('auditTrails.user')
            ->paginate(10);
        return view('inventory.index', compact('items'));
    }

    public function create()
    {
        return view('inventory.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'department' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'supplier' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'quantity' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'status' => 'required|string|max:255',
        ]);
        $data['user_id'] = $request->user()->id;
        InventoryItem::create($data);
        return redirect()->route('inventory.index');
    }

    public function edit(InventoryItem $inventoryItem)
    {
        if ($inventoryItem->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $inventoryItem->load('auditTrails.user');
        return view('inventory.edit', compact('inventoryItem'));
    }

    public function update(Request $request, InventoryItem $inventoryItem)
    {
        if ($inventoryItem->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'department' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'supplier' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'quantity' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'status' => 'required|string|max:255',
        ]);
        $inventoryItem->update($data);
        return redirect()->route('inventory.index');
    }

    public function destroy(InventoryItem $inventoryItem)
    {
        if ($inventoryItem->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $inventoryItem->delete();
        return redirect()->route('inventory.index');
    }
}
