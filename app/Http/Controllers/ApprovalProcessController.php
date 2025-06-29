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
        $processes = ApprovalProcess::with('stages.assignedUser')
            ->paginate($perPage)
            ->withQueryString();

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
        $users = User::orderBy('name')->get();

        return view('settings.approval-processes.create', compact('modules', 'departments', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'module' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'stages' => 'required|array|min:1',
            'stages.*.name' => 'required|string|max:255',
            'stages.*.position' => 'required|integer|min:1',
            'stages.*.assigned_user_id' => 'nullable|exists:users,id',
        ]);

        $stages = $data['stages'];
        unset($data['stages']);

        $process = ApprovalProcess::create($data);
        $process->stages()->createMany($stages);

        return redirect()
            ->route('approval-processes.index')
            ->with('success', 'Approval process created.');
    }

    public function show(ApprovalProcess $approvalProcess)
    {
        $approvalProcess->load('stages.assignedUser');

        return view('settings.approval-processes.show', compact('approvalProcess'));
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

        if ($approvalProcess->stages()->count() === 0) {
            return back()->withErrors(['stages' => 'At least one stage is required.'])->withInput();
        }

        $approvalProcess->update($data);

        return redirect()
            ->route('approval-processes.edit', $approvalProcess)
            ->with('success', 'Approval process updated.');
    }

    public function destroy(ApprovalProcess $approvalProcess)
    {
        $approvalProcess->delete();

        return redirect()
            ->route('approval-processes.index')
            ->with('success', 'Approval process deleted.');
    }

    public function storeStage(Request $request, ApprovalProcess $approvalProcess)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|integer|min:1',
            'assigned_user_id' => 'nullable|exists:users,id',
        ]);

        $approvalProcess->stages()->create($data);
        $this->reorderStages($approvalProcess);
        $approvalProcess->refresh()->load('stages.assignedUser');

        if ($request->expectsJson()) {
            $users = User::orderBy('name')->get();
            $html = view('settings.approval-processes.partials.stage_rows', compact('approvalProcess', 'users'))->render();

            return response()->json([
                'html' => $html,
                'message' => 'Stage added.',
            ]);
        }

        return redirect()
            ->route('approval-processes.edit', $approvalProcess)
            ->with('success', 'Stage added.');
    }

    public function updateStage(Request $request, ApprovalProcess $approvalProcess, ApprovalStage $stage)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|integer|min:1',
            'assigned_user_id' => 'nullable|exists:users,id',
        ]);

        $stage->update($data);

        $this->reorderStages($approvalProcess);
        $approvalProcess->refresh()->load('stages.assignedUser');

        if ($request->expectsJson()) {
            $users = User::orderBy('name')->get();
            $html = view('settings.approval-processes.partials.stage_rows', compact('approvalProcess', 'users'))->render();

            return response()->json([
                'html' => $html,
                'message' => 'Stage updated.',
            ]);
        }

        return redirect()
            ->route('approval-processes.edit', $approvalProcess)
            ->with('success', 'Stage updated.');
    }

    public function destroyStage(ApprovalProcess $approvalProcess, ApprovalStage $stage)
    {
        $stage->delete();

        $this->reorderStages($approvalProcess);
        $approvalProcess->refresh()->load('stages.assignedUser');

        if (request()->expectsJson()) {
            $users = User::orderBy('name')->get();
            $html = view('settings.approval-processes.partials.stage_rows', compact('approvalProcess', 'users'))->render();

            return response()->json([
                'html' => $html,
                'message' => 'Stage deleted.',
            ]);
        }

        return redirect()
            ->route('approval-processes.edit', $approvalProcess)
            ->with('success', 'Stage deleted.');
    }

    public function stages(ApprovalProcess $approvalProcess)
    {
        $approvalProcess->load('stages.assignedUser');
        $users = User::orderBy('name')->get();
        $html = view('settings.approval-processes.partials.stage_rows', compact('approvalProcess', 'users'))->render();

        return response()->json(['html' => $html]);
    }

    private function reorderStages(ApprovalProcess $approvalProcess): void
    {
        $approvalProcess->load('stages');

        $approvalProcess->stages
            ->sortBy('position')
            ->values()
            ->each(function (ApprovalStage $stage, int $index) {
                $stage->update(['position' => $index + 1]);
            });
    }
}
