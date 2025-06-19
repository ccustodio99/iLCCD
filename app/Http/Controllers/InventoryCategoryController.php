<?php

namespace App\Http\Controllers;

use App\Models\InventoryCategory;
use App\Rules\NoCategoryCycle;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InventoryCategoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);
        $categories = InventoryCategory::with('parent')
            ->paginate($perPage)
            ->withQueryString();

        return view('settings.inventory-categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = InventoryCategory::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('settings.inventory-categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:inventory_categories,name',
            'parent_id' => ['nullable', 'exists:inventory_categories,id', new NoCategoryCycle],
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $data['is_active'] ?? false;

        InventoryCategory::create($data);

        return redirect()->route('inventory-categories.index');
    }

    public function edit(InventoryCategory $inventoryCategory)
    {
        $parents = InventoryCategory::whereNull('parent_id')
            ->where('id', '!=', $inventoryCategory->id)
            ->orderBy('name')
            ->get();

        return view('settings.inventory-categories.edit', compact('inventoryCategory', 'parents'));
    }

    public function update(Request $request, InventoryCategory $inventoryCategory)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('inventory_categories', 'name')->ignore($inventoryCategory->id),
            ],
            'parent_id' => ['nullable', 'exists:inventory_categories,id', Rule::notIn([$inventoryCategory->id]), new NoCategoryCycle($inventoryCategory)],
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $data['is_active'] ?? false;

        $inventoryCategory->update($data);

        return redirect()->route('inventory-categories.index');
    }

    public function destroy(InventoryCategory $inventoryCategory)
    {
        if ($inventoryCategory->inventoryItems()->exists()) {
            return redirect()->route('inventory-categories.index')
                ->with('error', 'Category is referenced by inventory items and cannot be deleted.');
        }

        $inventoryCategory->delete();

        return redirect()->route('inventory-categories.index')
            ->with('success', 'Category deleted.');
    }

    public function disable(InventoryCategory $inventoryCategory)
    {
        $inventoryCategory->update(['is_active' => false]);

        return redirect()->route('inventory-categories.index');
    }
}
