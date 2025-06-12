<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\JobOrder;
use App\Models\Requisition;
use App\Models\PurchaseOrder;
use App\Models\DocumentLog;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request, 5);

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

        return view('dashboard', compact(
            'tickets',
            'jobOrders',
            'requisitions',
            'purchaseOrders',
            'incomingDocuments',
            'outgoingDocuments',
            'forApprovalDocuments'
        ));
    }
}
