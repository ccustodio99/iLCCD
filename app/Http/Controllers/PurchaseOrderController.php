<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class PurchaseOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:head,admin')->except(['index', 'downloadAttachment']);
    }
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);

        $query = PurchaseOrder::with(['auditTrails.user', 'user']);

        $user = auth()->user();
        if (!($user->role === 'admin' || ($user->role === 'head' && $user->department === 'Finance Office'))) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('supplier')) {
            $query->where('supplier', 'like', '%' . $request->input('supplier') . '%');
        }

        if ($request->filled('department')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('department', $request->input('department'));
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }

        $orders = $query->paginate($perPage)->withQueryString();

        $statuses = PurchaseOrder::STATUSES;
        $departments = \App\Models\User::select('department')
            ->distinct()
            ->whereNotNull('department')
            ->orderBy('department')
            ->pluck('department');

        return view('purchase_orders.index', compact('orders', 'statuses', 'departments'));
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
            'supplier' => 'nullable|string|max:255',
            'item' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'status' => ['sometimes', 'string', Rule::in(PurchaseOrder::STATUSES)],
            'attachment' => 'nullable|file|max:2048',
        ]);

        $data['user_id'] = $request->user()->id;
        $data['status'] = $data['status'] ?? PurchaseOrder::STATUS_DRAFT;

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')
                ->store('purchase_order_attachments', 'public');
        }

        PurchaseOrder::create($data);

        return redirect()->route('purchase-orders.index');
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        $user = auth()->user();
        $allowed = $user->role === 'admin' || ($user->role === 'head' && $user->department === 'Finance Office');
        if ($purchaseOrder->user_id !== $user->id && !$allowed) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $purchaseOrder->load('auditTrails.user');
        return view('purchase_orders.edit', compact('purchaseOrder'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $user = auth()->user();
        $allowed = $user->role === 'admin' || ($user->role === 'head' && $user->department === 'Finance Office');
        if ($purchaseOrder->user_id !== $user->id && !$allowed) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $data = $request->validate([
            'requisition_id' => 'required|integer|exists:requisitions,id',
            'inventory_item_id' => 'nullable|integer|exists:inventory_items,id',
            'supplier' => 'nullable|string|max:255',
            'item' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'status' => ['required', 'string', Rule::in(PurchaseOrder::STATUSES)],
            'attachment' => 'nullable|file|max:2048',
        ]);

        if ($request->hasFile('attachment')) {
            if ($purchaseOrder->attachment_path) {
                Storage::disk('public')->delete($purchaseOrder->attachment_path);
            }
            $data['attachment_path'] = $request->file('attachment')
                ->store('purchase_order_attachments', 'public');
        }

        $purchaseOrder->update($data);

        if ($data['status'] === PurchaseOrder::STATUS_ORDERED && $purchaseOrder->ordered_at === null) {
            $purchaseOrder->ordered_at = now();
            $purchaseOrder->save();
        }
        if ($data['status'] === PurchaseOrder::STATUS_RECEIVED && $purchaseOrder->received_at === null) {
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
        $user = auth()->user();
        $allowed = $user->role === 'admin' || ($user->role === 'head' && $user->department === 'Finance Office');
        if ($purchaseOrder->user_id !== $user->id && !$allowed) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $purchaseOrder->delete();
        return redirect()->route('purchase-orders.index');
    }

    public function downloadAttachment(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->attachment_path === null) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $user = auth()->user();
        $allowed = $user->role === 'admin' || ($user->role === 'head' && $user->department === 'Finance Office');
        if ($purchaseOrder->user_id !== $user->id && !$allowed) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }

        return Storage::disk('public')->download($purchaseOrder->attachment_path);
    }
}
