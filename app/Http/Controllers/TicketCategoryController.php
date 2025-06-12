<?php

namespace App\Http\Controllers;

use App\Models\TicketCategory;
use Illuminate\Http\Request;

class TicketCategoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);
        $categories = TicketCategory::paginate($perPage)->withQueryString();

        return view('settings.ticket-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('settings.ticket-categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $data['is_active'] ?? false;

        TicketCategory::create($data);

        return redirect()->route('ticket-categories.index');
    }

    public function edit(TicketCategory $ticketCategory)
    {
        return view('settings.ticket-categories.edit', compact('ticketCategory'));
    }

    public function update(Request $request, TicketCategory $ticketCategory)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $data['is_active'] ?? false;

        $ticketCategory->update($data);

        return redirect()->route('ticket-categories.index');
    }

    public function destroy(TicketCategory $ticketCategory)
    {
        $ticketCategory->delete();

        return redirect()->route('ticket-categories.index');
    }
}
