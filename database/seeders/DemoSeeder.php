<?php

namespace Database\Seeders;

use App\Models\AuditTrail;
use App\Models\Document;
use App\Models\DocumentLog;
use App\Models\DocumentVersion;
use App\Models\InventoryItem;
use App\Models\JobOrder;
use App\Models\PurchaseOrder;
use App\Models\DocumentCategory;
use App\Models\Requisition;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\RequisitionItem;
use App\Models\InventoryTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Demo users
        $admin = User::factory()->create([
            'name' => 'Demo Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'admin',
            'department' => 'ITRC',
        ]);

        $staff = User::factory()->create([
            'name' => 'Demo Staff',
            'email' => 'staff@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'staff',
            'department' => 'CCS',
        ]);

        $user = User::factory()->create([
            'name' => 'Demo User',
            'email' => 'user@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'user',
            'department' => 'CCS',
        ]);

        $president = User::factory()->create([
            'name' => 'Demo President',
            'email' => 'president@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'president',
            'department' => 'Administration',
        ]);

        $finance = User::factory()->create([
            'name' => 'Demo Finance',
            'email' => 'finance@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'finance',
            'department' => 'Finance Office',
        ]);

        $registrar = User::factory()->create([
            'name' => 'Demo Registrar',
            'email' => 'registrar@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'registrar',
            'department' => 'Registrar',
        ]);

        $hr = User::factory()->create([
            'name' => 'Demo HR',
            'email' => 'hr@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'hr',
            'department' => 'HR Department',
        ]);

        $clinic = User::factory()->create([
            'name' => 'Demo Clinic',
            'email' => 'clinic@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'clinic',
            'department' => 'Clinic',
        ]);

        $itrc = User::factory()->create([
            'name' => 'Demo ITRC',
            'email' => 'itrc@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'itrc',
            'department' => 'ITRC',
        ]);

        $head = User::factory()->create([
            'name' => 'Demo Head',
            'email' => 'head@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'head',
            'department' => 'CCS',
        ]);

        $faculty = User::factory()->create([
            'name' => 'Demo Faculty',
            'email' => 'faculty@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'staff',
            'department' => 'Faculty/Staff',
        ]);

        $academic = User::factory()->create([
            'name' => 'Demo Academic',
            'email' => 'academic@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'academic',
            'department' => 'Academic Units',
        ]);

        // Tickets
        $tickets = Ticket::factory()
            ->count(5)
            ->for($user)
            ->sequence(
                ['status' => 'open'],
                ['status' => 'open'],
                ['status' => 'resolved', 'resolved_at' => now()->subDay()],
                ['status' => 'escalated', 'escalated_at' => now()->subHours(2)],
                ['status' => 'open', 'archived_at' => now()->subDays(3)]
            )
            ->create();

        $watchers = [$admin->id, $itrc->id, $head->id];

        $tickets->each(function (Ticket $ticket) use ($watchers, $admin) {
            $ticket->watchers()->sync($watchers);

            TicketComment::factory()->for($ticket)->for($ticket->user)->create([
                'comment' => 'Initial issue details',
            ]);

            TicketComment::factory()->for($ticket)->for($admin)->create([
                'comment' => 'Acknowledged by admin',
            ]);

            AuditTrail::factory()->create([
                'auditable_id' => $ticket->id,
                'auditable_type' => Ticket::class,
                'user_id' => $ticket->user_id,
                'ip_address' => '127.0.0.1',
                'action' => 'created',
            ]);
        });

        // Job Orders linked to first three tickets
        $jobOrders = $tickets->take(3)->map(function (Ticket $ticket, $index) use ($staff) {
            return JobOrder::factory()
                ->for($ticket)
                ->for($ticket->user)
                ->for($staff, 'assignedTo')
                ->state(function () use ($index) {
                    return [
                        'status' => $index === 0 ? 'in_progress' : ($index === 1 ? 'completed' : 'new'),
                        'approved_at' => now()->subDays(2),
                        'started_at' => now()->subDay(),
                        'completed_at' => $index === 1 ? now()->subHours(1) : null,
                    ];
                })
                ->create();
        });

        $jobOrders->each(function (JobOrder $jo) {
            AuditTrail::factory()->create([
                'auditable_id' => $jo->id,
                'auditable_type' => JobOrder::class,
                'user_id' => $jo->user_id,
                'ip_address' => '127.0.0.1',
                'action' => 'created',
            ]);
        });

        // Requisitions linked to tickets and job orders
        $tickets->take(3)->each(function (Ticket $ticket, $index) use ($user, $jobOrders) {
            $req = Requisition::factory()
                ->for($user)
                ->for($ticket)
                ->for($jobOrders[$index])
                ->state([
                    'status' => 'approved',
                    'approved_by_id' => $user->id,
                    'approved_at' => now()->subDay(),
                ])
                ->create();

            AuditTrail::factory()->create([
                'auditable_id' => $req->id,
                'auditable_type' => Requisition::class,
                'user_id' => $user->id,
                'ip_address' => '127.0.0.1',
                'action' => 'created',
            ]);

            RequisitionItem::factory()->count(2)->for($req)->create();
        });

        // Additional requisition not tied to job order
        $extraReq = Requisition::factory()
            ->for($user)
            ->for($tickets->last())
            ->create();

        AuditTrail::factory()->create([
            'auditable_id' => $extraReq->id,
            'auditable_type' => Requisition::class,
            'user_id' => $user->id,
            'ip_address' => '127.0.0.1',
            'action' => 'created',
        ]);

        RequisitionItem::factory()->count(2)->for($extraReq)->create();

        // Inventory items
        $items = InventoryItem::factory()
            ->count(8)
            ->for($admin)
            ->state(new Sequence(
                ['status' => 'available'],
                ['status' => 'reserved'],
                ['status' => 'available'],
                ['status' => 'maintenance'],
                ['status' => 'available'],
                ['status' => 'available'],
                ['status' => 'reserved'],
                ['status' => 'available'],
            ))
            ->create();

        $items->each(function (InventoryItem $item, $index) use ($admin, $user, $jobOrders) {
            AuditTrail::factory()->create([
                'auditable_id' => $item->id,
                'auditable_type' => InventoryItem::class,
                'user_id' => $admin->id,
                'ip_address' => '127.0.0.1',
                'action' => 'created',
            ]);

            if ($index < 2) {
                InventoryTransaction::factory()
                    ->for($item)
                    ->for($user)
                    ->for($jobOrders[$index])
                    ->state(['action' => 'issue', 'quantity' => 1])
                    ->create();

                InventoryTransaction::factory()
                    ->for($item)
                    ->for($user)
                    ->for($jobOrders[$index])
                    ->state(['action' => 'return', 'quantity' => 1])
                    ->create();
            }
        });

        // Purchase orders referencing requisitions and inventory
        $requisitions = Requisition::limit(2)->get();
        $requisitions->each(function (Requisition $req, $index) use ($admin, $items) {
            $po = PurchaseOrder::factory()
                ->for($admin)
                ->for($req)
                ->for($items[$index])
                ->state([
                    'status' => $index === 0 ? 'ordered' : 'draft',
                    'ordered_at' => now()->subDays(1),
                ])
                ->create();

            AuditTrail::factory()->create([
                'auditable_id' => $po->id,
                'auditable_type' => PurchaseOrder::class,
                'user_id' => $admin->id,
                'ip_address' => '127.0.0.1',
                'action' => 'created',
            ]);
        });

        $docCategories = collect(['Policy', 'Syllabus', 'Report'])->map(function ($name) {
            return DocumentCategory::create([
                'name' => $name,
                'is_active' => true,
            ]);
        });

        // Documents with versions, logs and audit trails
        $documents = Document::factory()->count(3)
            ->for($admin)
            ->state(new Sequence(
                ['document_category_id' => $docCategories[0]->id],
                ['document_category_id' => $docCategories[1]->id],
                ['document_category_id' => $docCategories[2]->id]
            ))
            ->create();
        $documents->each(function (Document $document) use ($admin) {
            $versions = DocumentVersion::factory()->count(3)
                ->for($document)
                ->for($admin, 'uploader')
                ->create();

            $versions->each(function (DocumentVersion $version) use ($document, $admin) {
                DocumentLog::factory()
                    ->for($document)
                    ->for($admin)
                    ->create(['action' => 'upload']);

                AuditTrail::factory()->create([
                    'auditable_id' => $version->id,
                    'auditable_type' => DocumentVersion::class,
                    'user_id' => $admin->id,
                    'ip_address' => '127.0.0.1',
                    'action' => 'uploaded',
                ]);
            });

            AuditTrail::factory()->create([
                'auditable_id' => $document->id,
                'auditable_type' => Document::class,
                'user_id' => $admin->id,
                'ip_address' => '127.0.0.1',
                'action' => 'created',
            ]);
        });
    }
}
