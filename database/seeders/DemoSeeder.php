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
use App\Models\TicketCategory;
use App\Models\InventoryCategory;
use App\Models\Requisition;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\RequisitionItem;
use App\Models\InventoryTransaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Demo users seeded separately
        $admin = User::where('role', 'admin')->first();
        $staff = User::where('role', 'staff')->first();
        $itrc = User::where('role', 'itrc')->first();
        $head = User::where('role', 'head')->first();

        // Additional demo users for variety in ownership/assignment
        $extraUsers = User::factory()->count(10)->create();

        $ticketData = [
            [
                'category' => 'Desktops & Laptops',
                'subject' => 'Laptop not booting',
                'description' => 'The Dell laptop used by the registrar will not power on even when plugged in.',
            ],
            [
                'category' => 'Wi-Fi / Wired Access',
                'subject' => 'Wi-Fi connectivity issue in faculty room',
                'description' => 'Faculty in Room 201 report that the wireless network drops every few minutes.',
            ],
            [
                'category' => 'Classroom AV (Projectors, Interactive Whiteboards, Smart Displays)',
                'subject' => 'Request new projector installation',
                'description' => 'Need an Epson projector installed in Classroom 3 before the start of classes.',
            ],
            [
                'category' => 'Printers & Scanners',
                'subject' => 'Printer jam in admin office',
                'description' => 'The HP LaserJet printer in the admin office jams after every few pages.',
            ],
            [
                'category' => 'VPN & Remote Access',
                'subject' => 'VPN cannot connect from off campus',
                'description' => 'User cannot establish a VPN connection when working from home.',
            ],
        ];

        $jobOrderData = [
            [
                'type' => 'IT Equipment Setup (matches Computers & Devices)',
                'description' => 'Install new printers across the finance department.',
            ],
            [
                'type' => 'Preventative Maintenance (links Facilities & Maintenance)',
                'description' => 'Perform preventive maintenance on classroom projectors.',
            ],
            [
                'type' => 'Safety & Compliance Audits (e.g. Fire Extinguisher Tests)',
                'description' => 'Inspect fire extinguishers around the campus.',
            ],
            [
                'type' => 'Critical Network/Server Downtime (matches Network Outages)',
                'description' => 'Address network outage affecting the library.',
            ],
            [
                'type' => 'Hardware Upgrades (RAM, Storage)',
                'description' => 'Upgrade RAM in staff computers.',
            ],
            [
                'type' => 'Lab Equipment Calibration (links Laboratory Equipment)',
                'description' => 'Calibrate laboratory microscopes.',
            ],
        ];

        $requisitionPurposes = [
            'Purchase new laptops for the computer lab.',
            'Acquire projector bulbs for classrooms.',
            'Order office supplies for the finance office.',
            'Request chemicals for the chemistry laboratory.',
        ];

        $requisitionItems = [
            ['item' => 'Dell Latitude Laptop', 'specification' => '14-inch, 16GB RAM'],
            ['item' => 'Projector Bulb', 'specification' => 'Compatible with Epson models'],
            ['item' => 'Bond Paper', 'specification' => 'A4 size, 80gsm'],
            ['item' => 'Hydrochloric Acid', 'specification' => 'Laboratory grade'],
        ];

        $inventoryData = [
            [
                'category' => 'Computers & Laptops',
                'name' => 'Dell Latitude 5420 Laptop',
                'description' => '14-inch business laptop with Windows 11',
                'location' => 'IT Storage Room',
            ],
            [
                'category' => 'Printers & Scanners',
                'name' => 'HP LaserJet Pro M404 Printer',
                'description' => 'Monochrome laser printer for departmental use',
                'location' => 'Finance Office',
            ],
            [
                'category' => 'Networking Gear (Routers, Switches)',
                'name' => 'Cisco Catalyst 2960X Switch',
                'description' => '24-port network switch for building backbone',
                'location' => 'Administration Building',
            ],
            [
                'category' => 'AV Equipment (Projectors, Microphones)',
                'name' => 'Epson PowerLite X49 Projector',
                'description' => 'Classroom projector with HDMI input',
                'location' => 'Laboratory A',
            ],
            [
                'category' => 'Furniture & Fixtures',
                'name' => 'Steelcase Leap Office Chair',
                'description' => 'Adjustable ergonomic chair',
                'location' => 'Faculty Room',
            ],
            [
                'category' => 'Printers & Scanners',
                'name' => 'Brother ADS-2700W Scanner',
                'description' => 'Wireless document scanner',
                'location' => 'Finance Office',
            ],
            [
                'category' => 'Laboratory Equipment',
                'name' => 'Vernier pH Sensor',
                'description' => 'Sensor for chemistry lab experiments',
                'location' => 'Laboratory A',
            ],
            [
                'category' => 'Furniture & Fixtures',
                'name' => '3M Mobile Whiteboard',
                'description' => 'Portable whiteboard with stand',
                'location' => 'Faculty Room',
            ],
        ];

        $transactionPurposes = [
            'Issued for classroom upgrade',
            'Used during faculty training',
            'Returned after repair',
            'Loaned for off-site event',
        ];

        $ticketComments = [
            'We will check the power adapter and battery.',
            'Issue resolved after resetting the access point.',
            'Projector installation is scheduled for tomorrow.',
            'Printer cleaned and rollers replaced.',
            'VPN settings updated; please try again.',
        ];

        $ticketCategories = TicketCategory::whereIn('name', collect($ticketData)->pluck('category'))
            ->get()
            ->keyBy('name');

        $tickets = collect($ticketData)->map(function ($data) use ($extraUsers, $ticketCategories) {
            $status = fake()->randomElement(['open', 'resolved', 'escalated', 'closed']);

            return Ticket::factory()
                ->recycle($extraUsers)
                ->state([
                    'ticket_category_id' => $ticketCategories[$data['category']]->id ?? null,
                    'subject' => $data['subject'],
                    'description' => $data['description'],
                    'status' => $status,
                    'resolved_at' => in_array($status, ['resolved', 'closed']) ? now()->subDays(fake()->numberBetween(1, 5)) : null,
                    'escalated_at' => $status === 'escalated' ? now()->subDays(fake()->numberBetween(1, 5)) : null,
                    'archived_at' => $status === 'closed' ? now()->subDays(fake()->numberBetween(1, 5)) : null,
                ])
                ->create();
        });

        $watchers = [$admin->id, $itrc->id, $head->id];

        $tickets->each(function (Ticket $ticket) use ($watchers, $admin, $ticketComments) {
            $ticket->watchers()->sync($watchers);

            TicketComment::factory()->for($ticket)->for($ticket->user)->create([
                'comment' => collect($ticketComments)->random(),
            ]);

            TicketComment::factory()->for($ticket)->for($admin)->create([
                'comment' => collect($ticketComments)->random(),
            ]);

            AuditTrail::factory()->create([
                'auditable_id' => $ticket->id,
                'auditable_type' => Ticket::class,
                'user_id' => $ticket->user_id,
                'ip_address' => '127.0.0.1',
                'action' => 'created',
            ]);
        });

        // Job Orders linked to random tickets
        $jobOrders = collect($jobOrderData)->map(function ($data) use ($tickets, $staff) {
            $ticket = $tickets->random();
            $status = fake()->randomElement(JobOrder::STATUSES);

            return JobOrder::factory()
                ->for($ticket)
                ->for($ticket->user)
                ->for($staff, 'assignedTo')
                ->state([
                    'job_type' => $data['type'],
                    'description' => $data['description'],
                    'status' => $status,
                    'approved_at' => now()->subDays(fake()->numberBetween(1, 5)),
                    'started_at' => now()->subDays(fake()->numberBetween(1, 3)),
                    'completed_at' => in_array($status, ['completed', 'closed']) ? now()->subDay() : null,
                ])
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
        $requisitions = collect(range(1, 4))->map(function () use ($tickets, $jobOrders, $extraUsers, $requisitionPurposes, $requisitionItems) {
            $ticket = $tickets->random();
            $jobOrder = $jobOrders->random();
            $user = $extraUsers->random();

            $req = Requisition::factory()
                ->for($user)
                ->for($ticket)
                ->for($jobOrder)
                ->state([
                    'purpose' => collect($requisitionPurposes)->random(),
                    'status' => fake()->randomElement(Requisition::STATUSES),
                    'approved_by_id' => $user->id,
                    'approved_at' => now()->subDays(fake()->numberBetween(1, 5)),
                ])
                ->create();

            AuditTrail::factory()->create([
                'auditable_id' => $req->id,
                'auditable_type' => Requisition::class,
                'user_id' => $user->id,
                'ip_address' => '127.0.0.1',
                'action' => 'created',
            ]);

            RequisitionItem::factory()->count(2)->for($req)->state(function () use ($requisitionItems) {
                $data = collect($requisitionItems)->random();
                return [
                    'item' => $data['item'],
                    'specification' => $data['specification'],
                ];
            })->create();

            return $req;
        });

        $inventoryCategories = InventoryCategory::whereIn('name', collect($inventoryData)->pluck('category'))
            ->get()
            ->keyBy('name');

        $items = collect($inventoryData)->map(function ($data) use ($admin, $inventoryCategories) {
            return InventoryItem::factory()
                ->for($admin)
                ->state([
                    'inventory_category_id' => $inventoryCategories[$data['category']]->id ?? null,
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'location' => $data['location'],
                    'status' => fake()->randomElement([
                        InventoryItem::STATUS_AVAILABLE,
                        InventoryItem::STATUS_RESERVED,
                        InventoryItem::STATUS_MAINTENANCE,
                    ]),
                ])
                ->create();
        });

        $items->each(function (InventoryItem $item) use ($admin, $jobOrders, $extraUsers, $transactionPurposes) {
            AuditTrail::factory()->create([
                'auditable_id' => $item->id,
                'auditable_type' => InventoryItem::class,
                'user_id' => $admin->id,
                'ip_address' => '127.0.0.1',
                'action' => 'created',
            ]);

            InventoryTransaction::factory()
                ->for($item)
                ->for($extraUsers->random())
                ->for($jobOrders->random())
                ->state([
                    'action' => 'issue',
                    'quantity' => 1,
                    'purpose' => collect($transactionPurposes)->random(),
                ])
                ->create();

            InventoryTransaction::factory()
                ->for($item)
                ->for($extraUsers->random())
                ->for($jobOrders->random())
                ->state([
                    'action' => 'return',
                    'quantity' => 1,
                    'purpose' => collect($transactionPurposes)->random(),
                ])
                ->create();
        });

        // Purchase orders referencing requisitions and inventory
        $purchaseOrders = collect(range(1, 2))->map(function () use ($admin, $requisitions, $items) {
            $po = PurchaseOrder::factory()
                ->for($admin)
                ->for($requisitions->random())
                ->for($items->random())
                ->state([
                    'status' => collect([PurchaseOrder::STATUS_DRAFT, PurchaseOrder::STATUS_ORDERED])->random(),
                    'ordered_at' => now()->subDays(fake()->numberBetween(1, 5)),
                ])
                ->create();

            AuditTrail::factory()->create([
                'auditable_id' => $po->id,
                'auditable_type' => PurchaseOrder::class,
                'user_id' => $admin->id,
                'ip_address' => '127.0.0.1',
                'action' => 'created',
            ]);

            return $po;
        });

        $documentData = [
            [
                'category' => 'Policies & Procedures',
                'title' => 'Employee Handbook 2025',
                'description' => 'General policies for all employees.',
            ],
            [
                'category' => 'Forms & Templates',
                'title' => 'Requisition Form Template',
                'description' => 'Template used for purchase requisitions.',
            ],
            [
                'category' => 'Course Materials',
                'title' => 'Intro to Programming Syllabus',
                'description' => 'Syllabus for first-year programming course.',
            ],
            [
                'category' => 'Financial & Accounting',
                'title' => 'Annual Financial Report 2024',
                'description' => 'Report detailing the previous year financials.',
            ],
            [
                'category' => 'Research & Publications',
                'title' => 'Research Ethics Guidelines',
                'description' => 'Guidelines for conducting research responsibly.',
            ],
        ];

        $docCategories = DocumentCategory::whereIn('name', collect($documentData)->pluck('category'))
            ->get()
            ->keyBy('name');

        // Documents with versions, logs and audit trails
        $documents = collect($documentData)->map(function ($data) use ($admin, $docCategories) {
            return Document::factory()
                ->for($admin)
                ->state([
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'document_category_id' => $docCategories[$data['category']]->id ?? null,
                ])
                ->create();
        });
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
