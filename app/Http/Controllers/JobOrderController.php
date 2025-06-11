<?php

namespace App\Http\Controllers;

use App\Models\JobOrder;
use App\Models\InventoryItem;
use App\Models\Requisition;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JobOrderController extends Controller
{
    public function index()
    {
        $jobOrders = JobOrder::with(['requisitions', 'ticket', 'auditTrails.user'])
            ->where('user_id', auth()->id())
            ->paginate(10);
        return view('job_orders.index', compact('jobOrders'));
    }

    public function create()
    {
        return view('job_orders.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'job_type' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        $data['user_id'] = $request->user()->id;
        $data['status'] = 'new';
        JobOrder::create($data);
        return redirect()->route('job-orders.index');
    }

    public function edit(JobOrder $jobOrder)
    {
        if ($jobOrder->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        return view('job_orders.edit', compact('jobOrder'));
    }

    public function update(Request $request, JobOrder $jobOrder)
    {
        if ($jobOrder->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $data = $request->validate([
            'job_type' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string',
        ]);
        $jobOrder->update($data);
        if ($data['status'] === 'completed' && $jobOrder->completed_at === null) {
            $jobOrder->completed_at = now();
            $jobOrder->save();
        }
        return redirect()->route('job-orders.index');
    }

    public function complete(JobOrder $jobOrder)
    {
        if ($jobOrder->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }

        if ($jobOrder->status !== 'completed') {
            $jobOrder->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        }

        return redirect()->route('job-orders.index');
    }

    public function destroy(JobOrder $jobOrder)
    {
        if ($jobOrder->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $jobOrder->delete();
        return redirect()->route('job-orders.index');
    }

    /**
     * Request materials for this job order. If inventory stock exists,
     * it is deducted. Otherwise a linked requisition is created.
     */
    public function requestMaterials(Request $request, JobOrder $jobOrder)
    {
        if ($jobOrder->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }

        $data = $request->validate([
            'item' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'specification' => 'nullable|string',
            'purpose' => 'required|string',
        ]);

        $item = InventoryItem::where('name', $data['item'])->first();
        if ($item && $item->quantity >= $data['quantity']) {
            $item->decrement('quantity', $data['quantity']);
        } else {
            $requisition = Requisition::create([
                'user_id' => $request->user()->id,
                'job_order_id' => $jobOrder->id,
                'department' => $request->user()->department,
                'purpose' => $data['purpose'],
                'status' => 'pending_head',
            ]);

            $requisition->items()->create([
                'item' => $data['item'],
                'quantity' => $data['quantity'],
                'specification' => $data['specification'] ?? null,
            ]);
        }

        return redirect()->route('job-orders.index');
    }
}
