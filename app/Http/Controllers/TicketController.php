<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use App\Models\JobOrder;
use App\Models\JobOrderType;
use App\Models\Requisition;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;
use App\Notifications\TicketStatusNotification;
use Illuminate\Contracts\Cache\TaggableStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            $user->notify(new TicketStatusNotification($ticket->id, $message));
        }
    }

    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);

        $user = $request->user();
        $query = Ticket::query()->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhere('assigned_to_id', $user->id)
                ->orWhereHas('watchers', function ($w) use ($user) {
                    $w->where('users.id', $user->id);
                });

            if ($user->role === 'head') {
                $q->orWhereHas('user', function ($uq) use ($user) {
                    $uq->where('department', $user->department);
                });
            }
        });

        if ($request->boolean('archived')) {
            $query->withTrashed();
        } else {
            $query->whereDoesntHave('archivedBy', function ($aq) use ($user) {
                $aq->where('users.id', $user->id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('ticket_category_id')) {
            $categoryId = $request->input('ticket_category_id');
            $query->whereHas('ticketCategory', function ($q) use ($categoryId) {
                $q->where('id', $categoryId)->orWhere('parent_id', $categoryId);
            });
        }

        if ($request->filled('assigned_to_id')) {
            $query->where('assigned_to_id', $request->input('assigned_to_id'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $repository = Cache::getStore() instanceof \Illuminate\Contracts\Cache\TaggableStore
            ? Cache::tags('tickets')
            : Cache::store();

        $cacheKey = 'tickets:index:'.md5($request->fullUrl());
        $tickets = $repository->remember($cacheKey, 300, function () use ($query, $perPage) {
            return $query->with([
                'assignedTo',
                'ticketCategory',
                'jobOrder',
                'requisitions',
            ])
                ->paginate($perPage)
                ->withQueryString();
        });

        $users = $repository->remember('tickets:users', 300, function () {
            return User::orderBy('name')->get();
        });

        $categories = $repository->remember('tickets:categories', 300, function () {
            return TicketCategory::whereNull('parent_id')
                ->with('children')
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        });

        $types = $repository->remember('tickets:types', 300, function () {
            return JobOrderType::whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('name')
                ->with('children')
                ->get();
        });

        $statuses = $repository->remember('tickets:statuses', 300, function () {
            return Ticket::select('status')->distinct()->pluck('status');
        });

        return view('tickets.index', compact(
            'tickets',
            'users',
            'categories',
            'types',
            'statuses'
        ));
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
        ]);
        $data['user_id'] = $request->user()->id;
        $data['status'] = Ticket::STATUS_PENDING_HEAD;
        if ($request->hasFile('attachment')) {
            try {
                $data['attachment_path'] = $request->file('attachment')->store('ticket_attachments', 'public');
            } catch (\Throwable $e) {
                Log::error('Failed to store ticket attachment: '.$e->getMessage());
            }
        }

        $ticket = Ticket::create($data);

        $watcherIds = User::whereIn('role', ['admin', 'itrc'])->pluck('id')->toArray();
        $head = User::where('role', 'head')
            ->where('department', $request->user()->department)
            ->first();
        if ($head) {
            $watcherIds[] = $head->id;
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

        $message = "Ticket #{$ticket->id} has been created.";

        if ($ticket->assigned_to_id) {
            $message = "Ticket #{$ticket->id} has been created and assigned to {$ticket->assignedTo->name}.";
        }

        $this->notifyStakeholders($ticket, $message);

        return redirect()->route('tickets.index');
    }

    public function update(Request $request, Ticket $ticket)
    {
        if (
            $ticket->user_id !== auth()->id() &&
            $ticket->assigned_to_id !== auth()->id() &&
            ! (auth()->user()->role === 'head' && auth()->user()->department === $ticket->user->department)
        ) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        if (auth()->user()->role !== 'head') {
            if ($ticket->jobOrder && $ticket->jobOrder->status !== JobOrder::STATUS_PENDING_HEAD) {
                abort(Response::HTTP_FORBIDDEN, 'Access denied');
            }
            if ($ticket->requisitions()->where('status', '!=', Requisition::STATUS_PENDING_HEAD)->exists()) {
                abort(Response::HTTP_FORBIDDEN, 'Access denied');
            }
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
        ]);

        if ($request->user()->role === 'staff' && $request->input('status') !== $ticket->status) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        if ($request->hasFile('attachment')) {
            $oldPath = $ticket->attachment_path;
            try {
                if ($oldPath) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                }
                $data['attachment_path'] = $request->file('attachment')->store('ticket_attachments', 'public');
            } catch (\Throwable $e) {
                Log::error('Failed to replace ticket attachment: '.$e->getMessage());
                $data['attachment_path'] = $oldPath;
            }
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
        if ($request->has('watchers')) {
            $watcherIds = User::whereIn('role', ['admin', 'itrc'])->pluck('id')->toArray();
            $head = User::where('role', 'head')
                ->where('department', $ticket->user->department)
                ->first();
            if ($head) {
                $watcherIds[] = $head->id;
            }
            $watcherIds = array_unique(array_merge($watcherIds, $request->input('watchers', [])));

            $originalWatchers = $ticket->watchers()->pluck('users.id')->toArray();
            $ticket->watchers()->sync($watcherIds);
            $sortedOriginal = $originalWatchers;
            sort($sortedOriginal);
            $sortedNew = $watcherIds;
            sort($sortedNew);
            if ($sortedNew !== $sortedOriginal) {
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

    public function requestEdit(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== $request->user()->id) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }

        $data = $request->validate([
            'reason' => 'required|string',
        ]);

        $ticket->update([
            'status' => 'open',
            'edit_request_reason' => $data['reason'],
            'edit_requested_at' => now(),
            'edit_requested_by' => $request->user()->id,
        ]);

        AuditTrail::create([
            'auditable_id' => $ticket->id,
            'auditable_type' => Ticket::class,
            'user_id' => $request->user()->id,
            'ip_address' => $request->ip(),
            'action' => 'edit_requested',
            'changes' => ['reason' => $data['reason']],
        ]);

        $this->notifyStakeholders($ticket, "Edit requested for ticket #{$ticket->id}.");

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

    public function modalDetails(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id() &&
            $ticket->assigned_to_id !== auth()->id() &&
            ! $ticket->watchers->contains(auth()->id())) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }

        $ticket->load('ticketCategory.parent');

        $repository = Cache::getStore() instanceof TaggableStore
            ? Cache::tags('tickets')
            : Cache::store();

        $jobOrderTypes = $repository->remember('tickets:types', 300, function () {
            return JobOrderType::whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('name')
                ->with('children')
                ->get();
        });

        $typeMap = $jobOrderTypes->mapWithKeys(function ($type) {
            return [$type->id => $type->children->map(fn ($c) => ['name' => $c->name])];
        });

        return view('tickets._modal_details', compact('ticket', 'jobOrderTypes', 'typeMap'));
    }

    public function modalEdit(Ticket $ticket)
    {
        if (
            $ticket->user_id !== auth()->id() &&
            $ticket->assigned_to_id !== auth()->id() &&
            ! (auth()->user()->role === 'head' && auth()->user()->department === $ticket->user->department)
        ) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }

        $users = User::orderBy('name')->get();

        $parentId = $ticket->ticketCategory->parent_id
            ?? $ticket->ticketCategory->id;

        $categories = TicketCategory::withTrashed()
            ->whereNull('parent_id')
            ->with(['children' => fn ($q) => $q->withTrashed()])
            ->where(function ($q) use ($parentId) {
                $q->where('is_active', true)
                    ->orWhere('id', $parentId);
            })
            ->orderBy('name')
            ->get();

        return view('tickets._modal_edit', compact('ticket', 'users', 'categories'));
    }

    public function modalConvertJobOrder(Ticket $ticket)
    {
        if (
            $ticket->user_id !== auth()->id() &&
            $ticket->assigned_to_id !== auth()->id() &&
            ! (auth()->user()->role === 'head' && auth()->user()->department === $ticket->user->department)
        ) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }

        $repository = Cache::getStore() instanceof TaggableStore
            ? Cache::tags('tickets')
            : Cache::store();

        $jobOrderTypes = $repository->remember('tickets:types', 300, function () {
            return JobOrderType::whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('name')
                ->with('children')
                ->get();
        });

        $typeMap = $jobOrderTypes->mapWithKeys(function ($type) {
            return [$type->id => $type->children->map(fn ($c) => ['name' => $c->name])];
        });

        return view('tickets.partials._modal_convert_job_order', compact('ticket', 'jobOrderTypes', 'typeMap'));
    }

    public function modalConvertRequisition(Ticket $ticket)
    {
        if (
            $ticket->user_id !== auth()->id() &&
            $ticket->assigned_to_id !== auth()->id() &&
            ! (auth()->user()->role === 'head' && auth()->user()->department === $ticket->user->department)
        ) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }

        return view('tickets.partials._modal_convert_requisition', compact('ticket'));
    }

    /** Show tickets awaiting department head approval */
    public function approvals(Request $request)
    {
        abort_unless(auth()->user()->role === 'head', Response::HTTP_FORBIDDEN);

        $perPage = $this->getPerPage($request);

        $tickets = Ticket::with('user')
            ->where('status', Ticket::STATUS_PENDING_HEAD)
            ->whereHas('user', function ($q) {
                $q->where('department', auth()->user()->department);
            })
            ->paginate($perPage)
            ->withQueryString();

        return view('tickets.approvals', compact('tickets'));
    }

    /** Approve the given ticket */
    public function approve(Ticket $ticket)
    {
        abort_unless(auth()->user()->role === 'head', Response::HTTP_FORBIDDEN);
        abort_if($ticket->status !== Ticket::STATUS_PENDING_HEAD, Response::HTTP_FORBIDDEN);
        abort_if($ticket->user->department !== auth()->user()->department, Response::HTTP_FORBIDDEN);

        $ticket->update([
            'status' => Ticket::STATUS_OPEN,
            'approved_by_id' => auth()->id(),
            'approved_at' => now(),
        ]);

        $ticket->load('watchers', 'assignedTo', 'user');
        $this->notifyStakeholders($ticket, "Ticket #{$ticket->id} was approved.");

        return redirect()->route('tickets.approvals');
    }

    public function destroy(Ticket $ticket)
    {
        if (
            $ticket->user_id !== auth()->id() &&
            $ticket->assigned_to_id !== auth()->id() &&
            ! (auth()->user()->role === 'head' && auth()->user()->department === $ticket->user->department)
        ) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        if (auth()->user()->role !== 'head') {
            if ($ticket->jobOrder && $ticket->jobOrder->status !== JobOrder::STATUS_PENDING_HEAD) {
                abort(Response::HTTP_FORBIDDEN, 'Access denied');
            }
            if ($ticket->requisitions()->where('status', '!=', Requisition::STATUS_PENDING_HEAD)->exists()) {
                abort(Response::HTTP_FORBIDDEN, 'Access denied');
            }
        }
        if ($ticket->status !== 'closed') {
            $ticket->status = 'closed';
            if ($ticket->resolved_at === null) {
                $ticket->resolved_at = now();
            }
            $ticket->save();
        }

        $ticket->archivedBy()->syncWithoutDetaching([
            auth()->id() => ['created_at' => now(), 'updated_at' => now()],
        ]);

        return redirect()->route('tickets.index');
    }

    public function convertToJobOrder(Request $request, Ticket $ticket)
    {
        if (
            $ticket->user_id !== auth()->id() &&
            $ticket->assigned_to_id !== auth()->id() &&
            ! (auth()->user()->role === 'head' && auth()->user()->department === $ticket->user->department)
        ) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }

        $data = $request->validateWithBag('convertJobOrder', [
            'type_parent' => ['required', 'exists:job_order_types,id'],
            'job_type' => [
                'required',
                'string',
                'max:255',
                Rule::exists('job_order_types', 'name')->where(function ($q) use ($request) {
                    $q->where('parent_id', $request->type_parent)
                        ->where('is_active', true);
                }),
            ],
            'description' => 'required|string',
            'attachment' => 'nullable|file|max:2048',
        ]);

        $type = JobOrderType::where('name', $data['job_type'])->first();
        $data['job_order_type_id'] = $type?->id;
        $data['user_id'] = $ticket->user_id;
        $data['ticket_id'] = $ticket->id;
        $data['status'] = JobOrder::STATUS_PENDING_HEAD;

        $attachmentPath = null;

        try {
            DB::transaction(function () use ($request, &$data, $ticket, &$attachmentPath) {
                if ($request->hasFile('attachment')) {
                    $attachmentPath = $request->file('attachment')
                        ->store('job_order_attachments', 'public');
                    $data['attachment_path'] = $attachmentPath;
                }

                unset($data['type_parent'], $data['job_type']);

                JobOrder::create($data);

                $ticket->update(['status' => 'converted']);
            });
        } catch (\Throwable $e) {
            if ($attachmentPath) {
                Storage::disk('public')->delete($attachmentPath);
            }
            Log::error('Failed to convert ticket to job order: '.$e->getMessage());
            throw $e;
        }

        return redirect()->route('job-orders.index');
    }

    public function convertToRequisition(Request $request, Ticket $ticket)
    {
        if (
            $ticket->user_id !== auth()->id() &&
            $ticket->assigned_to_id !== auth()->id() &&
            ! (auth()->user()->role === 'head' && auth()->user()->department === $ticket->user->department)
        ) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }

        $data = $request->validateWithBag('convertRequisition', [
            'item.*' => 'required|string|max:255',
            'quantity.*' => 'required|integer|min:1',
            'specification.*' => 'nullable|string',
            'purpose' => 'required|string',
            'attachment' => 'nullable|file|max:2048',
        ]);

        $requisitionData = [
            'user_id' => $ticket->user_id,
            'ticket_id' => $ticket->id,
            'department' => $ticket->user->department,
            'purpose' => $data['purpose'],
            'status' => Requisition::STATUS_PENDING_HEAD,
        ];

        $attachmentPath = null;

        try {
            DB::transaction(function () use ($request, &$requisitionData, $ticket, $data, &$attachmentPath) {
                if ($request->hasFile('attachment')) {
                    $attachmentPath = $request->file('attachment')
                        ->store('requisition_attachments', 'public');
                    $requisitionData['attachment_path'] = $attachmentPath;
                }

                $requisition = Requisition::create($requisitionData);

                foreach ($data['item'] as $i => $name) {
                    $requisition->items()->create([
                        'item' => $name,
                        'quantity' => $data['quantity'][$i] ?? 1,
                        'specification' => $data['specification'][$i] ?? null,
                    ]);
                }

                $ticket->update(['status' => 'converted']);
            });
        } catch (\Throwable $e) {
            if ($attachmentPath) {
                Storage::disk('public')->delete($attachmentPath);
            }
            Log::error('Failed to convert ticket to requisition: '.$e->getMessage());
            throw $e;
        }

        return redirect()->route('requisitions.index');
    }
}
