<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use App\Models\Ticket;
use App\Models\JobOrder;
use App\Models\Requisition;

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
}
