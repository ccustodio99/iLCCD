<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    /**
     * Display a listing of audit trail entries.
     */
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request, 20);

        $logs = AuditTrail::with('user')
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('audit_trails.index', compact('logs'));
    }
}
