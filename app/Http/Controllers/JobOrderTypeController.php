<?php

namespace App\Http\Controllers;

use App\Models\JobOrderType;
use Illuminate\Http\Request;

class JobOrderTypeController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);
        $types = JobOrderType::with('parent')
            ->paginate($perPage)
            ->withQueryString();

        return view('settings.job-order-types.index', compact('types'));
    }

    public function create()
    {
        $parents = JobOrderType::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('settings.job-order-types.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:job_order_types,name',
            'parent_id' => 'nullable|exists:job_order_types,id',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $data['is_active'] ?? false;

        JobOrderType::create($data);

        return redirect()->route('job-order-types.index');
    }

    public function edit(JobOrderType $jobOrderType)
    {
        $parents = JobOrderType::whereNull('parent_id')
            ->where('id', '!=', $jobOrderType->id)
            ->orderBy('name')
            ->get();

        return view('settings.job-order-types.edit', compact('jobOrderType', 'parents'));
    }

    public function update(Request $request, JobOrderType $jobOrderType)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:job_order_types,name,'.$jobOrderType->id,
            'parent_id' => 'nullable|exists:job_order_types,id',
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

    /**
     * Return active child types for the given parent.
     */
    public function children(JobOrderType $parent)
    {
        return $parent->children()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}
