<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\JobOrder;
use App\Models\Requisition;
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

    public function convertToJobOrder(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }

        JobOrder::create([
            'user_id' => $ticket->user_id,
            'job_type' => $ticket->category,
            'description' => $ticket->description,
            'status' => 'new',
        ]);

        $ticket->update(['status' => 'converted']);

        return redirect()->route('job-orders.index');
    }

    public function convertToRequisition(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }

        $requisition = Requisition::create([
            'user_id' => $ticket->user_id,
            'ticket_id' => $ticket->id,
            'department' => auth()->user()->department,
            'purpose' => $ticket->description,
            'status' => 'pending_head',
        ]);

        $requisition->items()->create([
            'item' => $ticket->subject,
            'quantity' => 1,
        ]);

        $ticket->update(['status' => 'converted']);

        return redirect()->route('requisitions.index');
    }
}
