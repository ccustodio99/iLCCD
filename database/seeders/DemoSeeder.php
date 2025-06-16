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

        // Additional demo users for variety in ownership/assignment
        $extraUsers = User::factory()->count(10)->create();

        $ticketSubjects = [
            'Laptop not booting',
            'Wi-Fi connectivity issue in faculty room',
            'Request new projector installation',
            'Printer jam in admin office',
            'VPN cannot connect from off campus',
        ];

        $ticketDescriptions = [
            'The Dell laptop used by the registrar will not power on even when plugged in.',
            'Faculty in Room 201 report that the wireless network drops every few minutes.',
            'Need an Epson projector installed in Classroom 3 before the start of classes.',
            'The HP LaserJet printer in the admin office jams after every few pages.',
            'User cannot establish a VPN connection when working from home.',
        ];

        $jobDescriptions = [
            'Install new printers across the finance department.',
            'Perform preventive maintenance on classroom projectors.',
            'Inspect fire extinguishers around the campus.',
            'Address network outage affecting the library.',
            'Upgrade RAM in staff computers.',
            'Calibrate laboratory microscopes.',
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

        $inventoryNames = [
            'Dell Latitude 5420 Laptop',
            'HP LaserJet Pro M404 Printer',
            'Cisco Catalyst 2960X Switch',
            'Epson PowerLite X49 Projector',
            'Steelcase Leap Office Chair',
            'Brother ADS-2700W Scanner',
            'Vernier pH Sensor',
            '3M Mobile Whiteboard',
        ];

        $inventoryDescriptions = [
            '14-inch business laptop with Windows 11',
            'Monochrome laser printer for departmental use',
            '24-port network switch for building backbone',
            'Classroom projector with HDMI input',
            'Adjustable ergonomic chair',
            'Wireless document scanner',
            'Sensor for chemistry lab experiments',
            'Portable whiteboard with stand',
        ];

        $inventoryLocations = [
            'IT Storage Room',
            'Finance Office',
            'Administration Building',
            'Laboratory A',
            'Faculty Room',
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

        // Tickets based on predefined subjects/descriptions
        $tickets = Ticket::factory()
            ->count(5)
            ->recycle($extraUsers)
            ->state(function () use ($ticketSubjects, $ticketDescriptions) {
                $status = fake()->randomElement(['open', 'resolved', 'escalated', 'closed']);
                $index = array_rand($ticketSubjects);
                return [
                    'subject' => $ticketSubjects[$index],
                    'description' => $ticketDescriptions[$index],
                    'status' => $status,
                    'resolved_at' => in_array($status, ['resolved', 'closed']) ? now()->subDays(fake()->numberBetween(1, 5)) : null,
                    'escalated_at' => $status === 'escalated' ? now()->subDays(fake()->numberBetween(1, 5)) : null,
                    'archived_at' => $status === 'closed' ? now()->subDays(fake()->numberBetween(1, 5)) : null,
                ];
            })
            ->create();

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
        $jobOrders = collect(range(1, 3))->map(function () use ($tickets, $staff, $jobDescriptions) {
            $ticket = $tickets->random();
            $status = fake()->randomElement(JobOrder::STATUSES);

            return JobOrder::factory()
                ->for($ticket)
                ->for($ticket->user)
                ->for($staff, 'assignedTo')
                ->state([
                    'description' => collect($jobDescriptions)->random(),
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

        // Inventory items
        $items = InventoryItem::factory()
            ->count(8)
            ->for($admin)
            ->state(function () use ($inventoryNames, $inventoryDescriptions, $inventoryLocations) {
                $index = array_rand($inventoryNames);
                return [
                    'name' => $inventoryNames[$index],
                    'description' => $inventoryDescriptions[$index],
                    'location' => collect($inventoryLocations)->random(),
                    'status' => fake()->randomElement([
                        InventoryItem::STATUS_AVAILABLE,
                        InventoryItem::STATUS_RESERVED,
                        InventoryItem::STATUS_MAINTENANCE,
                    ]),
                ];
            })
            ->create();

        $items->each(function (InventoryItem $item) use ($admin, $jobOrders, $extraUsers) {
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

        $docCategoryNames = [
            'Policies & Procedures',
            'Forms & Templates',
            'Course Materials',
        ];

        $documentTitles = [
            'Employee Handbook 2025',
            'Requisition Form Template',
            'Intro to Programming Syllabus',
            'Annual Financial Report 2024',
            'Research Ethics Guidelines',
        ];

        $documentDescriptions = [
            'General policies for all employees.',
            'Template used for purchase requisitions.',
            'Syllabus for first-year programming course.',
            'Report detailing the previous year financials.',
            'Guidelines for conducting research responsibly.',
        ];

        $docCategories = collect($docCategoryNames)->map(function ($name) {
            return DocumentCategory::firstOrCreate(
                ['name' => $name],
                ['is_active' => true]
            );
        });

        // Documents with versions, logs and audit trails
        $documents = Document::factory()->count(3)
            ->for($admin)
            ->state(function () use ($docCategories, $documentTitles, $documentDescriptions) {
                $index = array_rand($documentTitles);
                return [
                    'title' => $documentTitles[$index],
                    'description' => $documentDescriptions[$index],
                    'document_category_id' => $docCategories->random()->id,
                ];
            })
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
