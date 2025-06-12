<?php

namespace Database\Seeders;

use App\Models\TicketCategory;
use Illuminate\Database\Seeder;

class TicketCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Computers & Devices' => [
                'Desktops & Laptops',
                'Mobile Devices (Tablets, Chromebooks, Smartphones)',
                'Peripherals (Keyboards, Mice, Monitors, External Drives)',
                'Printers & Scanners',
                'Classroom AV (Projectors, Interactive Whiteboards, Smart Displays)',
            ],
            'Network & Access' => [
                'Wi-Fi / Wired Access',
                'VPN & Remote Access',
                'Campus Outages',
            ],
            'Facilities & Maintenance' => [
                'Preventative Maintenance',
                'HVAC Inspections',
                'Electrical Safety Checks',
                'Fire Extinguisher Tests',
                'Vehicle Inspections',
                'Repairs & Emergencies',
                'Electrical Outages',
                'Plumbing Leaks / Clogs',
                'HVAC Failures',
                'General Repairs (Doors, Furniture, Infrastructure)',
            ],
            'Procurement & Inventory' => [
                'Purchase Requisitions',
                'New Hardware/Software Requests',
                'Inventory Management',
                'Stock Level Inquiry',
                'Stock Adjustments',
                'Discrepancy Investigation',
            ],
            'Academics & Systems' => [
                'Learning Management (LMS)',
                'Student Information (SIS)',
                'Digital Resources & E-Library',
            ],
            'Security & Safety' => [
                'Cybersecurity',
                'Malware / Virus Incidents',
                'Phishing Reports',
                'Physical Security',
                'Access Card / Lock Issues',
            ],
            'Support & Training' => [
                'Workshops & Tutorials',
                'IT Skill Sessions',
                'LMS / SIS Training',
                'User Guides & Documentation',
                'Manual Requests',
                'FAQ Updates',
            ],
            'Feedback & Improvement' => [
                'Service Feedback',
                'Feature Requests',
                'Usability Suggestions',
            ],
            'Other / General Inquiry' => [
                'General Inquiry',
            ],
        ];

        foreach ($categories as $parent => $children) {
            $parentCategory = TicketCategory::create([
                'name' => $parent,
                'is_active' => true,
            ]);

            foreach ($children as $child) {
                TicketCategory::create([
                    'name' => $child,
                    'parent_id' => $parentCategory->id,
                    'is_active' => true,
                ]);
            }
        }
    }
}
