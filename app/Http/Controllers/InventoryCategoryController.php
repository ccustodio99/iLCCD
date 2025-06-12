<?php

namespace App\Http\Controllers;

use App\Models\InventoryCategory;
use Illuminate\Http\Request;

class InventoryCategoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);
        $categories = InventoryCategory::paginate($perPage)->withQueryString();

        return view('settings.inventory-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('settings.inventory-categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $data['is_active'] ?? false;

        InventoryCategory::create($data);

        return redirect()->route('inventory-categories.index');
    }

    public function edit(InventoryCategory $inventoryCategory)
    {
        return view('settings.inventory-categories.edit', compact('inventoryCategory'));
    }

    public function update(Request $request, InventoryCategory $inventoryCategory)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $data['is_active'] ?? false;

        $inventoryCategory->update($data);

        return redirect()->route('inventory-categories.index');
    }

    public function destroy(InventoryCategory $inventoryCategory)
    {
        $inventoryCategory->delete();

        return redirect()->route('inventory-categories.index');
    }

    public function disable(InventoryCategory $inventoryCategory)
    {
        $inventoryCategory->update(['is_active' => false]);

        return redirect()->route('inventory-categories.index');
    }
}
