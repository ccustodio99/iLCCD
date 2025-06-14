<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use App\Models\Ticket;
use App\Models\JobOrder;
use App\Models\Requisition;
use Illuminate\Http\Request;
use App\Exports\AuditTrailExport;
use Maatwebsite\Excel\Facades\Excel;

class KpiAuditDashboardController extends Controller
{
    /**
     * Display KPI metrics and recent audit logs.
     */
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);

        $logsQuery = AuditTrail::with('user')->latest();
        $this->applyFilters($logsQuery, $request);

        $ticketsQuery = Ticket::query();
        $jobOrdersQuery = JobOrder::query();
        $requisitionsQuery = Requisition::query();

        if ($request->filled('department')) {
            $dept = $request->input('department');
            $ticketsQuery->whereHas('user', fn ($q) => $q->where('department', $dept));
            $jobOrdersQuery->whereHas('user', fn ($q) => $q->where('department', $dept));
            $requisitionsQuery->where('department', $dept);
        }

        if ($request->filled('date_from')) {
            $from = $request->date('date_from');
            $ticketsQuery->whereDate('created_at', '>=', $from);
            $jobOrdersQuery->whereDate('created_at', '>=', $from);
            $requisitionsQuery->whereDate('created_at', '>=', $from);
            $logsQuery->whereDate('created_at', '>=', $from);
        }

        if ($request->filled('date_to')) {
            $to = $request->date('date_to');
            $ticketsQuery->whereDate('created_at', '<=', $to);
            $jobOrdersQuery->whereDate('created_at', '<=', $to);
            $requisitionsQuery->whereDate('created_at', '<=', $to);
            $logsQuery->whereDate('created_at', '<=', $to);
        }

        $ticketsCount = $ticketsQuery->count();
        $jobOrdersCount = $jobOrdersQuery->count();
        $requisitionsCount = $requisitionsQuery->count();

        $logs = $logsQuery
            ->paginate($perPage)
            ->withQueryString();

        $users = \App\Models\User::orderBy('name')->get();
        $modules = AuditTrail::select('auditable_type')->distinct()->get()
            ->mapWithKeys(fn ($m) => [$m->auditable_type => class_basename($m->auditable_type)]);
        $actions = AuditTrail::select('action')->distinct()->orderBy('action')->pluck('action');

        return view('kpi.dashboard', compact(
            'ticketsCount',
            'jobOrdersCount',
            'requisitionsCount',
            'logs',
            'users',
            'modules',
            'actions'
        ));
    }

    /**
     * Export audit logs to Excel.
     */
    public function export(Request $request)
    {
        $logsQuery = AuditTrail::with('user')->latest();
        $this->applyFilters($logsQuery, $request);

        $logs = $logsQuery->get();

        return Excel::download(AuditTrailExport::make($logs), 'kpi_audit_logs.xlsx');
    }

    private function applyFilters($query, Request $request): void
    {
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->integer('user_id'));
        }

        if ($request->filled('department')) {
            $dept = $request->input('department');
            $query->whereHas('user', fn ($q) => $q->where('department', $dept));
        }

        if ($request->filled('module')) {
            $query->where('auditable_type', $request->input('module'));
        }

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }
    }
}
