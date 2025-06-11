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
        $tickets = Ticket::query()
            ->where('status', '!=', 'closed')
            ->when($request->ticket_status, fn($q) => $q->where('status', $request->ticket_status))
            ->latest()
            ->paginate(5, ['*'], 'tickets_page');

        $jobOrders = JobOrder::query()
            ->whereNotIn('status', ['completed', 'closed'])
            ->when($request->job_status, fn($q) => $q->where('status', $request->job_status))
            ->latest()
            ->paginate(5, ['*'], 'job_orders_page');

        $requisitions = Requisition::query()
            ->where('status', '!=', 'closed')
            ->when($request->requisition_status, fn($q) => $q->where('status', $request->requisition_status))
            ->latest()
            ->paginate(5, ['*'], 'requisitions_page');

        $purchaseOrders = PurchaseOrder::query()
            ->where('status', '!=', 'received')
            ->when($request->po_status, fn($q) => $q->where('status', $request->po_status))
            ->latest()
            ->paginate(5, ['*'], 'purchase_orders_page');

        $incomingDocuments = DocumentLog::with(['document', 'user'])
            ->where('action', 'incoming')
            ->latest()
            ->paginate(5, ['*'], 'incoming_docs_page');

        $outgoingDocuments = DocumentLog::with(['document', 'user'])
            ->where('action', 'outgoing')
            ->latest()
            ->paginate(5, ['*'], 'outgoing_docs_page');

        $forApprovalDocuments = DocumentLog::with(['document', 'user'])
            ->where('action', 'for_approval')
            ->latest()
            ->paginate(5, ['*'], 'for_approval_docs_page');

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
