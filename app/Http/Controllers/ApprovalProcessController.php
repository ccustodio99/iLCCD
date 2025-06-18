<?php

namespace App\Http\Controllers;

use App\Models\ApprovalProcess;
use App\Models\ApprovalStage;
use App\Models\User;
use Illuminate\Http\Request;

class ApprovalProcessController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);
        $processes = ApprovalProcess::with('stages')->paginate($perPage)->withQueryString();

        return view('settings.approval-processes.index', compact('processes'));
    }

    public function create()
    {
        $modules = ApprovalProcess::MODULES;
        $departments = User::select('department')
            ->whereNotNull('department')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        return view('settings.approval-processes.create', compact('modules', 'departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'module' => 'required|string|max:255',
            'department' => 'required|string|max:255',
        ]);

        ApprovalProcess::create($data);

        return redirect()->route('approval-processes.index');
    }

    public function edit(ApprovalProcess $approvalProcess)
    {
        $approvalProcess = ApprovalProcess::with('stages.assignedUser')
            ->findOrFail($approvalProcess->id);
        $users = User::orderBy('name')->get();
        $modules = ApprovalProcess::MODULES;
        $departments = User::select('department')
            ->whereNotNull('department')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        return view('settings.approval-processes.edit', compact('approvalProcess', 'users', 'modules', 'departments'));
    }

    public function update(Request $request, ApprovalProcess $approvalProcess)
    {
        $data = $request->validate([
            'module' => 'required|string|max:255',
            'department' => 'required|string|max:255',
        ]);

        $approvalProcess->update($data);

        return redirect()->route('approval-processes.edit', $approvalProcess);
    }

    public function destroy(ApprovalProcess $approvalProcess)
    {
        $approvalProcess->delete();

        return redirect()->route('approval-processes.index');
    }

    public function storeStage(Request $request, ApprovalProcess $approvalProcess)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|integer|min:1',
            'assigned_user_id' => 'nullable|exists:users,id',
        ]);

        $approvalProcess->stages()->create($data);

        return redirect()->route('approval-processes.edit', $approvalProcess);
    }

    public function updateStage(Request $request, ApprovalProcess $approvalProcess, ApprovalStage $stage)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|integer|min:1',
            'assigned_user_id' => 'nullable|exists:users,id',
        ]);

        $stage->update($data);

        return redirect()->route('approval-processes.edit', $approvalProcess);
    }

    public function destroyStage(ApprovalProcess $approvalProcess, ApprovalStage $stage)
    {
        $stage->delete();

        return redirect()->route('approval-processes.edit', $approvalProcess);
    }
}
