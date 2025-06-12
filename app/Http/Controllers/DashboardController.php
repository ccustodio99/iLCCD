<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\JobOrder;
use App\Models\Requisition;
use App\Models\PurchaseOrder;
use App\Models\DocumentLog;
use App\Models\Announcement;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->getDashboardData($request);

        return view('dashboard', $data);
    }

    public function data(Request $request)
    {
        return response()->json($this->getDashboardData($request));
    }

    protected function getDashboardData(Request $request): array
    {
        $perPage = $this->getPerPage($request, 5);

        $params = $request->only([
            'ticket_status',
            'job_status',
            'requisition_status',
            'po_status',
            'tickets_page',
            'job_orders_page',
            'requisitions_page',
            'purchase_orders_page',
            'incoming_docs_page',
            'outgoing_docs_page',
            'for_approval_docs_page',
        ]);

        $params['per_page'] = $perPage;
        ksort($params);
        $cacheKey = 'dashboard:' . md5(json_encode($params));

        $repository = Cache::getStore() instanceof \Illuminate\Contracts\Cache\TaggableStore
            ? Cache::tags('dashboard')
            : Cache::store();

        return $repository->remember($cacheKey, 300, function () use ($request, $perPage) {
            $announcements = Announcement::where('is_active', true)
                ->latest()
                ->get();

            $tickets = Ticket::query()
                ->where('status', '!=', 'closed')
                ->when($request->ticket_status, fn($q) => $q->where('status', $request->ticket_status))
                ->latest()
                ->paginate($perPage, ['*'], 'tickets_page')
                ->withQueryString();

            $jobOrders = JobOrder::query()
                ->whereNotIn('status', ['completed', 'closed'])
                ->when($request->job_status, fn($q) => $q->where('status', $request->job_status))
                ->latest()
                ->paginate($perPage, ['*'], 'job_orders_page')
                ->withQueryString();

            $requisitions = Requisition::query()
                ->where('status', '!=', 'closed')
                ->when($request->requisition_status, fn($q) => $q->where('status', $request->requisition_status))
                ->latest()
                ->paginate($perPage, ['*'], 'requisitions_page')
                ->withQueryString();

            $purchaseOrders = PurchaseOrder::query()
                ->where('status', '!=', 'received')
                ->when($request->po_status, fn($q) => $q->where('status', $request->po_status))
                ->latest()
                ->paginate($perPage, ['*'], 'purchase_orders_page')
                ->withQueryString();

            $incomingDocuments = DocumentLog::with(['document', 'user'])
                ->where('action', 'incoming')
                ->latest()
                ->paginate($perPage, ['*'], 'incoming_docs_page')
                ->withQueryString();

            $outgoingDocuments = DocumentLog::with(['document', 'user'])
                ->where('action', 'outgoing')
                ->latest()
                ->paginate($perPage, ['*'], 'outgoing_docs_page')
                ->withQueryString();

            $forApprovalDocuments = DocumentLog::with(['document', 'user'])
                ->where('action', 'for_approval')
                ->latest()
                ->paginate($perPage, ['*'], 'for_approval_docs_page')
                ->withQueryString();

            return compact(
                'announcements',
                'tickets',
                'jobOrders',
                'requisitions',
                'purchaseOrders',
                'incomingDocuments',
                'outgoingDocuments',
                'forApprovalDocuments'
            );
        });
    }
}
