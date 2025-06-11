<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\DocumentLog;
use App\Models\DocumentVersion;
use App\Models\InventoryItem;
use App\Models\JobOrder;
use App\Models\PurchaseOrder;
use App\Models\Requisition;
use App\Models\Ticket;
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

        // Tickets
        $tickets = Ticket::factory()
            ->count(5)
            ->for($user)
            ->sequence(
                ['status' => 'open'],
                ['status' => 'open'],
                ['status' => 'resolved', 'resolved_at' => now()->subDay()],
                ['status' => 'escalated', 'escalated_at' => now()->subHours(2)],
                ['status' => 'open']
            )
            ->create();

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

        // Requisitions linked to tickets and job orders
        $tickets->take(3)->each(function (Ticket $ticket, $index) use ($user, $jobOrders) {
            Requisition::factory()
                ->for($user)
                ->for($ticket)
                ->for($jobOrders[$index])
                ->state([
                    'status' => 'approved',
                    'approved_by_id' => $user->id,
                    'approved_at' => now()->subDay(),
                ])
                ->create();
        });

        // Additional requisition not tied to job order
        Requisition::factory()
            ->for($user)
            ->for($tickets->last())
            ->create();

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

        // Purchase orders referencing requisitions and inventory
        $requisitions = Requisition::limit(2)->get();
        $requisitions->each(function (Requisition $req, $index) use ($admin, $items) {
            PurchaseOrder::factory()
                ->for($admin)
                ->for($req)
                ->for($items[$index])
                ->state([
                    'status' => $index === 0 ? 'ordered' : 'draft',
                    'ordered_at' => now()->subDays(1),
                ])
                ->create();
        });

        // Documents with versions and logs
        $documents = Document::factory()->count(3)->for($admin)->create();
        $documents->each(function (Document $document) use ($admin) {
            DocumentVersion::factory()->count(2)
                ->for($document)
                ->for($admin, 'uploader')
                ->create();

            DocumentLog::factory()
                ->for($document)
                ->for($admin)
                ->create(['action' => 'upload']);
        });
    }
}
