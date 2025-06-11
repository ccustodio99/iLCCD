<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\JobOrder;
use App\Models\Requisition;
use App\Models\User;
use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('user_id', auth()->id())
            ->with(['auditTrails.user', 'watchers', 'assignedTo'])
            ->paginate(10);
        $users = User::orderBy('name')->get();
        return view('tickets.index', compact('tickets', 'users'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('tickets.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_to_id' => 'nullable|exists:users,id',
            'due_at' => 'nullable|date',
            'watchers' => 'array',
            'watchers.*' => 'exists:users,id',
        ]);
        $data['user_id'] = $request->user()->id;
        $data['status'] = 'open';
        $ticket = Ticket::create($data);

        $watcherIds = User::whereIn('role', ['admin', 'itrc'])->pluck('id')->toArray();
        $head = User::where('role', 'head')
            ->where('department', $request->user()->department)
            ->first();
        if ($head) {
            $watcherIds[] = $head->id;
        }
        if (isset($data['watchers'])) {
            $watcherIds = array_unique(array_merge($watcherIds, $data['watchers']));
        }
        $ticket->watchers()->sync($watcherIds);

        $watcherNames = User::whereIn('id', $watcherIds)->pluck('name')->join(', ');

        AuditTrail::create([
            'auditable_id' => $ticket->id,
            'auditable_type' => Ticket::class,
            'user_id' => $request->user()->id,
            'ip_address' => $request->ip(),
            'action' => 'watchers_updated',
            'changes' => [
                'watchers' => [
                    'old' => null,
                    'new' => $watcherNames,
                ],
            ],
        ]);

        if ($ticket->assigned_to_id) {
            AuditTrail::create([
                'auditable_id' => $ticket->id,
                'auditable_type' => Ticket::class,
                'user_id' => $request->user()->id,
                'ip_address' => $request->ip(),
                'action' => 'assigned',
                'comment' => 'Assigned to: ' . $ticket->assignedTo?->name,
            ]);
        }

        return redirect()->route('tickets.index');
    }

    public function edit(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id() && $ticket->assigned_to_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $users = User::orderBy('name')->get();
        return view('tickets.edit', compact('ticket', 'users'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id() && $ticket->assigned_to_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $data = $request->validate([
            'category' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_to_id' => 'nullable|exists:users,id',
            'status' => 'required|string',
            'due_at' => 'nullable|date',
            'watchers' => 'array',
            'watchers.*' => 'exists:users,id',
        ]);
        $ticket->update($data);
        if ($ticket->wasChanged('assigned_to_id')) {
            AuditTrail::create([
                'auditable_id' => $ticket->id,
                'auditable_type' => Ticket::class,
                'user_id' => $request->user()->id,
                'ip_address' => $request->ip(),
                'action' => 'assigned',
                'comment' => 'Assigned to: ' . $ticket->assignedTo?->name,
            ]);
        }
        if ($data['status'] === 'closed' && $ticket->resolved_at === null) {
            $ticket->resolved_at = now();
            $ticket->save();
        }
        $watcherIds = User::whereIn('role', ['admin', 'itrc'])->pluck('id')->toArray();
        $head = User::where('role', 'head')
            ->where('department', $ticket->user->department)
            ->first();
        if ($head) {
            $watcherIds[] = $head->id;
        }
        if (isset($data['watchers'])) {
            $watcherIds = array_unique(array_merge($watcherIds, $data['watchers']));
        }
        $originalWatchers = $ticket->watchers()->pluck('users.id')->toArray();
        $ticket->watchers()->sync($watcherIds);
        if ($watcherIds !== array_values($originalWatchers)) {
            $oldWatcherNames = User::whereIn('id', $originalWatchers)->pluck('name')->join(', ');
            $newWatcherNames = User::whereIn('id', $watcherIds)->pluck('name')->join(', ');
            AuditTrail::create([
                'auditable_id' => $ticket->id,
                'auditable_type' => Ticket::class,
                'user_id' => $request->user()->id,
                'ip_address' => $request->ip(),
                'action' => 'watchers_updated',
                'changes' => [
                    'watchers' => [
                        'old' => $oldWatcherNames,
                        'new' => $newWatcherNames,
                    ],
                ],
            ]);
        }
        return redirect()->route('tickets.index');
    }

    public function destroy(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id() && $ticket->assigned_to_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $ticket->delete();
        return redirect()->route('tickets.index');
    }

    public function convertToJobOrder(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id() && $ticket->assigned_to_id !== auth()->id()) {
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
        if ($ticket->user_id !== auth()->id() && $ticket->assigned_to_id !== auth()->id()) {
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
