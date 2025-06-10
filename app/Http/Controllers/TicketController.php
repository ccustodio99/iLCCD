<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('user_id', auth()->id())->paginate(10);
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'due_at' => 'nullable|date',
        ]);
        $data['user_id'] = $request->user()->id;
        $data['status'] = 'open';
        Ticket::create($data);
        return redirect()->route('tickets.index');
    }

    public function edit(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        return view('tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $data = $request->validate([
            'category' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string',
            'due_at' => 'nullable|date',
        ]);
        $ticket->update($data);
        if ($data['status'] === 'closed' && $ticket->resolved_at === null) {
            $ticket->resolved_at = now();
            $ticket->save();
        }
        return redirect()->route('tickets.index');
    }

    public function destroy(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $ticket->delete();
        return redirect()->route('tickets.index');
    }
}
