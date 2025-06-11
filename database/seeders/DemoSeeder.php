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
        $tickets = Ticket::factory()->count(3)->for($user)->create();

        // Job Orders linked to tickets
        $jobOrders = $tickets->map(function (Ticket $ticket) use ($staff) {
            return JobOrder::factory()
                ->for($ticket)
                ->for($ticket->user)
                ->for($staff, 'assignedTo')
                ->create();
        });

        // Requisitions linked to tickets and job orders
        $tickets->each(function (Ticket $ticket) use ($user) {
            Requisition::factory()
                ->for($user)
                ->for($ticket)
                ->create();
        });

        // Inventory items
        $items = InventoryItem::factory()->count(5)->for($admin)->create();

        // Purchase orders referencing requisitions and inventory
        $requisition = Requisition::first();
        PurchaseOrder::factory()->count(2)->for($admin)
            ->for($requisition)
            ->for($items->first())
            ->create();

        // Documents with versions and logs
        $documents = Document::factory()->count(2)->for($admin)->create();
        $documents->each(function (Document $document) use ($admin) {
            DocumentVersion::factory()
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
