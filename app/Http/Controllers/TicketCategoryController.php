<?php

namespace App\Http\Controllers;

use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TicketCategoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);
        $categories = TicketCategory::with('parent')
            ->paginate($perPage)
            ->withQueryString();

        return view('settings.ticket-categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = TicketCategory::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('settings.ticket-categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('ticket_categories', 'name')],
            'parent_id' => 'nullable|exists:ticket_categories,id',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $data['is_active'] ?? false;

        TicketCategory::create($data);

        return redirect()->route('ticket-categories.index');
    }

    public function edit(TicketCategory $ticketCategory)
    {
        $parents = TicketCategory::whereNull('parent_id')
            ->where('id', '!=', $ticketCategory->id)
            ->orderBy('name')
            ->get();

        return view('settings.ticket-categories.edit', compact('ticketCategory', 'parents'));
    }

    public function update(Request $request, TicketCategory $ticketCategory)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('ticket_categories', 'name')->ignore($ticketCategory->id)],
            'parent_id' => 'nullable|exists:ticket_categories,id',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $data['is_active'] ?? false;

        $ticketCategory->update($data);

        return redirect()->route('ticket-categories.index');
    }

    public function destroy(TicketCategory $ticketCategory)
    {
        if ($ticketCategory->children()->exists()) {
            $ticketCategory->children()->delete();
        }

        $ticketCategory->delete();

        return redirect()->route('ticket-categories.index');
    }
}
