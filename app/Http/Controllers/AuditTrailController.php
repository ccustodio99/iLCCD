<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;

class AuditTrailController extends Controller
{
    /**
     * Display a listing of audit trail entries.
     */
    public function index()
    {
        $logs = AuditTrail::with('user')
            ->latest()
            ->paginate(20);

        return view('audit_trails.index', compact('logs'));
    }
}
