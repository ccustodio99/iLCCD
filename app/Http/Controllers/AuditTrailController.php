<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use App\Models\User;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    /**
     * Display a listing of audit trail entries.
     */
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request, 20);

        $query = AuditTrail::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        if ($request->filled('auditable_type')) {
            $query->where('auditable_type', $request->input('auditable_type'));
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->input('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->input('to'));
        }

        $logs = $query->latest()->paginate($perPage)->withQueryString();

        $users = User::orderBy('name')->get();
        $actions = AuditTrail::select('action')->distinct()->orderBy('action')->pluck('action');
        $modules = AuditTrail::select('auditable_type')->distinct()->orderBy('auditable_type')->pluck('auditable_type');

        return view('audit_trails.index', compact('logs', 'users', 'actions', 'modules'));
    }
}
