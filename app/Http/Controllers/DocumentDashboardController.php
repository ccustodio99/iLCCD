<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\DocumentLog;
use App\Models\DocumentVersion;

class DocumentDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $logsQuery = DocumentLog::with(['document', 'user'])->latest();
        $metricsQuery = Document::query();

        if ($user->role !== 'admin') {
            $logsQuery->whereHas('document', function ($q) use ($user) {
                $q->where('department', $user->department);
            });
            $metricsQuery->where('department', $user->department);
        }

        $totalUploads = $metricsQuery->count();
        $totalVersions = DocumentVersion::whereIn('document_id', $metricsQuery->pluck('id'))->count();
        $recentLogs = $logsQuery->take(10)->get();

        return view('documents.dashboard', compact('totalUploads', 'totalVersions', 'recentLogs'));
    }
}
