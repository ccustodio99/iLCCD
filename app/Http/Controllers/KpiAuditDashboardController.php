<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use App\Models\Ticket;
use App\Models\JobOrder;
use App\Models\Requisition;
use App\Exports\AuditTrailExport;
use Maatwebsite\Excel\Facades\Excel;

class KpiAuditDashboardController extends Controller
{
    /**
     * Display KPI metrics and recent audit logs.
     */
    public function index()
    {
        $ticketsCount = Ticket::count();
        $jobOrdersCount = JobOrder::count();
        $requisitionsCount = Requisition::count();

        $logs = AuditTrail::with('user')
            ->latest()
            ->paginate(10);

        return view('kpi.dashboard', compact(
            'ticketsCount',
            'jobOrdersCount',
            'requisitionsCount',
            'logs'
        ));
    }

    /**
     * Export audit logs to Excel.
     */
    public function export()
    {
        $logs = AuditTrail::with('user')->latest()->get();

        return Excel::download(new AuditTrailExport($logs), 'kpi_audit_logs.xlsx');
    }
}
