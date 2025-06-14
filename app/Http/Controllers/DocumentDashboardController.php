<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\DocumentLog;
use App\Models\DocumentVersion;

class DocumentDashboardController extends Controller
{
    public function index(Request $request)
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

        if ($request->filled('user_id')) {
            $logsQuery->where('user_id', $request->integer('user_id'));
            $metricsQuery->where('user_id', $request->integer('user_id'));
        }

        if ($request->filled('department')) {
            $logsQuery->whereHas('document', fn ($q) => $q->where('department', $request->input('department')));
            $metricsQuery->where('department', $request->input('department'));
        }

        if ($request->filled('document_category_id')) {
            $logsQuery->whereHas('document', fn ($q) => $q->where('document_category_id', $request->integer('document_category_id')));
            $metricsQuery->where('document_category_id', $request->integer('document_category_id'));
        }

        $totalUploads = $metricsQuery->count();
        $documentIdsQuery = $metricsQuery->clone()->select('id');
        $totalVersions = DocumentVersion::whereIn('document_id', $documentIdsQuery)->count();

        $perPage = $this->getPerPage($request);
        $recentLogs = $logsQuery->paginate($perPage)->withQueryString();

        $users = \App\Models\User::orderBy('name')->get();
        $categories = \App\Models\DocumentCategory::where('is_active', true)->get();

        return view('documents.dashboard', compact('totalUploads', 'totalVersions', 'recentLogs', 'users', 'categories'));
    }
}
