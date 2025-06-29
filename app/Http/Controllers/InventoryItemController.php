<?php

namespace App\Http\Controllers;

use App\Models\InventoryCategory;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class InventoryItemController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);

        $query = InventoryItem::where('user_id', auth()->id());

        if ($request->filled('category')) {
            $categoryId = $request->input('category');
            $query->whereHas('inventoryCategory', fn ($q) => $q->where('id', $categoryId));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $items = $query->with(['auditTrails.user', 'transactions.user', 'inventoryCategory'])
            ->paginate($perPage)
            ->withQueryString();

        $categories = InventoryCategory::whereNull('parent_id')
            ->with('children')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        $statuses = InventoryItem::select('status')->distinct()->pluck('status');

        return view('inventory.index', compact('items', 'categories', 'statuses'));
    }

    public function create()
    {
        $categories = InventoryCategory::whereNull('parent_id')
            ->with('children')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('inventory.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sku' => 'required|string|max:255|unique:inventory_items,sku',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'inventory_category_id' => [
                'nullable',
                Rule::exists('inventory_categories', 'id')->where('is_active', true),
            ],
            'department' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'supplier' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'quantity' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'status' => ['required', Rule::in(InventoryItem::STATUSES)],
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
        $categories = InventoryCategory::whereNull('parent_id')
            ->with('children')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('inventory.edit', compact('inventoryItem', 'categories'));
    }

    public function update(Request $request, InventoryItem $inventoryItem)
    {
        if ($inventoryItem->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $data = $request->validate([
            'sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('inventory_items', 'sku')->ignore($inventoryItem->id),
            ],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'inventory_category_id' => [
                'nullable',
                Rule::exists('inventory_categories', 'id')->where('is_active', true),
            ],
            'department' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'supplier' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'quantity' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'status' => ['required', Rule::in(InventoryItem::STATUSES)],
        ]);
        $previousQuantity = $inventoryItem->quantity;
        $inventoryItem->update($data);
        $inventoryItem->refresh();
        $this->handleLowStock($inventoryItem, $previousQuantity, $request->user()->id);

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
            'purpose' => 'nullable|string',
        ]);

        if ($inventoryItem->quantity < $data['quantity']) {
            return back()->withErrors(['quantity' => 'Not enough stock']);
        }

        $previousQuantity = $inventoryItem->quantity;

        $inventoryItem->decrement('quantity', $data['quantity']);
        $inventoryItem->refresh();
        $this->handleLowStock($inventoryItem, $previousQuantity, $request->user()->id);

        InventoryTransaction::create([
            'inventory_item_id' => $inventoryItem->id,
            'user_id' => $request->user()->id,
            'action' => 'issue',
            'quantity' => $data['quantity'],
            'purpose' => $data['purpose'] ?? null,
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
            'purpose' => 'nullable|string',
        ]);

        $previousQuantity = $inventoryItem->quantity;

        $inventoryItem->increment('quantity', $data['quantity']);
        $inventoryItem->refresh();
        $this->handleLowStock($inventoryItem, $previousQuantity, $request->user()->id);

        InventoryTransaction::create([
            'inventory_item_id' => $inventoryItem->id,
            'user_id' => $request->user()->id,
            'action' => 'return',
            'quantity' => $data['quantity'],
            'purpose' => $data['purpose'] ?? null,
        ]);

        return redirect()->route('inventory.index');
    }

    private function handleLowStock(InventoryItem $item, int $previousQuantity, int $actorId): void
    {
        $crossedBelow = $previousQuantity > $item->minimum_stock
            && $item->quantity <= $item->minimum_stock
            && $item->low_stock_notified_at === null;

        if ($crossedBelow) {
            $recipients = User::where('department', $item->department)
                ->whereIn('role', ['staff', 'head'])
                ->where('id', '<>', $actorId)
                ->get();

            foreach ($recipients as $recipient) {
                $recipient->notify(new LowStockNotification($item->name, $item->quantity));
            }

            $item->update(['low_stock_notified_at' => now()]);
        } elseif ($item->quantity > $item->minimum_stock && $item->low_stock_notified_at) {
            $item->update(['low_stock_notified_at' => null]);
        }
    }
}
