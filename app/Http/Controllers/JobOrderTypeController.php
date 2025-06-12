<?php

namespace App\Http\Controllers;

use App\Models\JobOrderType;
use Illuminate\Http\Request;

class JobOrderTypeController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);
        $types = JobOrderType::paginate($perPage)->withQueryString();

        return view('settings.job-order-types.index', compact('types'));
    }

    public function create()
    {
        return view('settings.job-order-types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $data['is_active'] ?? false;

        JobOrderType::create($data);

        return redirect()->route('job-order-types.index');
    }

    public function edit(JobOrderType $jobOrderType)
    {
        return view('settings.job-order-types.edit', compact('jobOrderType'));
    }

    public function update(Request $request, JobOrderType $jobOrderType)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $data['is_active'] ?? false;

        $jobOrderType->update($data);

        return redirect()->route('job-order-types.index');
    }

    public function destroy(JobOrderType $jobOrderType)
    {
        $jobOrderType->delete();

        return redirect()->route('job-order-types.index');
    }

    public function disable(JobOrderType $jobOrderType)
    {
        $jobOrderType->update(['is_active' => false]);

        return redirect()->route('job-order-types.index');
    }
}
