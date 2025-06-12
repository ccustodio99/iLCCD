<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\JobOrder;
use App\Models\Requisition;
use App\Models\User;
use App\Models\AuditTrail;
use App\Models\TicketCategory;
use App\Notifications\TicketStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends Controller
{
    private function notifyStakeholders(Ticket $ticket, string $message): void
    {
        $recipients = collect([$ticket->user]);

        if ($ticket->assignedTo) {
            $recipients->push($ticket->assignedTo);
        }

        $recipients = $recipients->merge($ticket->watchers)->unique('id');

        foreach ($recipients as $user) {
            $user->notify(new TicketStatusNotification($message));
        }
    }
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);
        $tickets = Ticket::where('user_id', auth()->id())
            ->with(['auditTrails.user', 'watchers', 'assignedTo', 'comments.user'])
            ->paginate($perPage)
            ->withQueryString();

        $users = User::orderBy('name')->get();
        $categories = TicketCategory::whereNull('parent_id')
            ->with('children')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('tickets.index', compact('tickets', 'users', 'categories'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $categories = TicketCategory::whereNull('parent_id')
            ->with('children')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        return view('tickets.create', compact('users', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ticket_category_id' => [
                'required',
                Rule::exists('ticket_categories', 'id')->where('is_active', true),
            ],
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_to_id' => 'nullable|exists:users,id',
            'due_at' => 'nullable|date',
            'attachment' => 'nullable|file|max:2048',
            'watchers' => 'array',
            'watchers.*' => 'exists:users,id',
        ]);
        $data['user_id'] = $request->user()->id;
        $data['status'] = 'open';
        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('ticket_attachments', 'public');
        }

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
                'changes' => [
                    'assigned_to_id' => [
                        'old' => null,
                        'new' => $ticket->assigned_to_id,
                    ],
                ],
            ]);
        }

        $ticket->load('watchers', 'assignedTo', 'user');
        $this->notifyStakeholders($ticket, "Ticket #{$ticket->id} has been created.");

        if ($ticket->assigned_to_id) {
            $this->notifyStakeholders(
                $ticket,
                "Ticket #{$ticket->id} has been assigned to {$ticket->assignedTo->name}."
            );
        }

        return redirect()->route('tickets.index');
    }

    public function edit(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id() && $ticket->assigned_to_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $users = User::orderBy('name')->get();
        $categories = TicketCategory::whereNull('parent_id')
            ->with('children')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        return view('tickets.edit', compact('ticket', 'users', 'categories'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id() && $ticket->assigned_to_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $data = $request->validate([
            'ticket_category_id' => [
                'required',
                Rule::exists('ticket_categories', 'id')->where('is_active', true),
            ],
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_to_id' => 'nullable|exists:users,id',
            'status' => 'required|string',
            'due_at' => 'nullable|date',
            'attachment' => 'nullable|file|max:2048',
            'watchers' => 'array',
            'watchers.*' => 'exists:users,id',
        ]);
        if ($request->hasFile('attachment')) {
            if ($ticket->attachment_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($ticket->attachment_path);
            }
            $data['attachment_path'] = $request->file('attachment')->store('ticket_attachments', 'public');
        }

        $ticket->update($data);
        if ($ticket->wasChanged('assigned_to_id')) {
            AuditTrail::create([
                'auditable_id' => $ticket->id,
                'auditable_type' => Ticket::class,
                'user_id' => $request->user()->id,
                'ip_address' => $request->ip(),
                'action' => 'assigned',
                'changes' => [
                    'assigned_to_id' => [
                        'old' => $ticket->getOriginal('assigned_to_id'),
                        'new' => $ticket->assigned_to_id,
                    ],
                ],
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

        $ticket->load('watchers', 'assignedTo', 'user');

        if ($ticket->wasChanged('assigned_to_id')) {
            $this->notifyStakeholders(
                $ticket,
                "Ticket #{$ticket->id} has been assigned to {$ticket->assignedTo->name}."
            );
        }

        if ($ticket->wasChanged('status') && $ticket->status === 'escalated') {
            if ($ticket->escalated_at === null) {
                $ticket->escalated_at = now();
                $ticket->save();
            }
            $this->notifyStakeholders($ticket, "Ticket #{$ticket->id} has been escalated.");
        } else {
            $this->notifyStakeholders($ticket, "Ticket #{$ticket->id} has been updated.");
        }
        return redirect()->route('tickets.index');
    }

    public function storeComment(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== $request->user()->id &&
            $ticket->assigned_to_id !== $request->user()->id &&
            ! $ticket->watchers->contains($request->user()->id)) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }

        $data = $request->validate([
            'comment' => 'required|string',
        ]);

        $ticket->comments()->create([
            'user_id' => $request->user()->id,
            'comment' => $data['comment'],
        ]);

        $ticket->load('watchers', 'assignedTo', 'user');
        $this->notifyStakeholders($ticket, "New comment on ticket #{$ticket->id}.");

        return back();
    }

    public function downloadAttachment(Ticket $ticket)
    {
        if ($ticket->attachment_path === null) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if ($ticket->user_id !== auth()->id() &&
            $ticket->assigned_to_id !== auth()->id() &&
            ! $ticket->watchers->contains(auth()->id())) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }

        return Storage::disk('public')->download($ticket->attachment_path);
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
            'ticket_id' => $ticket->id,
            'job_type' => $ticket->ticketCategory->name,
            'description' => $ticket->description,
            'status' => JobOrder::STATUS_PENDING_HEAD,
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
            'department' => $ticket->user->department,
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
