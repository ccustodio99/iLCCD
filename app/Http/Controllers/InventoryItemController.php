<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use App\Models\InventoryCategory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InventoryItemController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);

        $items = InventoryItem::where('user_id', auth()->id())
            ->with(['auditTrails.user', 'transactions.user'])
            ->paginate($perPage)
            ->withQueryString();

        return view('inventory.index', compact('items'));
    }

    public function create()
    {
        $categories = InventoryCategory::where('is_active', true)->orderBy('name')->pluck('name');

        return view('inventory.create', compact('categories'));
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
        $categories = InventoryCategory::where('is_active', true)->orderBy('name')->pluck('name');

        return view('inventory.edit', compact('inventoryItem', 'categories'));
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

    /**
     * Issue quantity of an inventory item and record transaction.
     */
    public function issue(Request $request, InventoryItem $inventoryItem)
    {
        if ($inventoryItem->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }

        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($inventoryItem->quantity < $data['quantity']) {
            return back()->withErrors(['quantity' => 'Not enough stock']);
        }

        $inventoryItem->decrement('quantity', $data['quantity']);

        InventoryTransaction::create([
            'inventory_item_id' => $inventoryItem->id,
            'user_id' => $request->user()->id,
            'action' => 'issue',
            'quantity' => $data['quantity'],
        ]);

        return redirect()->route('inventory.index');
    }

    /**
     * Return quantity of an inventory item and record transaction.
     */
    public function return(Request $request, InventoryItem $inventoryItem)
    {
        if ($inventoryItem->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }

        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $inventoryItem->increment('quantity', $data['quantity']);

        InventoryTransaction::create([
            'inventory_item_id' => $inventoryItem->id,
            'user_id' => $request->user()->id,
            'action' => 'return',
            'quantity' => $data['quantity'],
        ]);

        return redirect()->route('inventory.index');
    }
}
