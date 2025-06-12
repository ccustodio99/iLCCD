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
                'Classroom AV (Projectors, Interactive Whiteboards, Smart Displays)',
                'Printers & Scanners',
            ],
            'Software & Apps' => [
                'Operating Systems (Install, Upgrade, Patching)',
                'Applications (Installation, Licensing, Crashes)',
                'Third-Party Tools (Adobe, Zoom, etc.)',
                'Performance Issues (Slow Boot, Hangups)',
            ],
            'Network & Access' => [
                'Wi-Fi / Wired Access',
                'VPN & Remote Access',
                'Network Outages',
            ],
            'User Accounts & Access' => [
                'Password Resets / Unlocks',
                'New Account Onboarding',
                'Permissions & Role Changes',
            ],
            'Printing & Scanning' => [
                'Print Queue Errors',
                'Print Quality (Smudges, Streaks)',
                'Scanner Setup & Integration',
            ],
            'Procurement & Inventory' => [
                'New Hardware/Software Requests',
            ],
            'Facilities & Maintenance' => [
                'Preventative Maintenance',
                'Repairs & Emergencies',
            ],
            'Security & Safety' => [
                'Malware / Virus Incidents',
                'Phishing Reports',
            ],
            'Training & Support' => [
                'Workshops & Tutorials',
                'User Guides & Documentation',
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
            $parentCategory = TicketCategory::firstOrCreate(
                ['name' => $parent],
                ['is_active' => true]
            );

            foreach ($children as $child) {
                TicketCategory::firstOrCreate(
                    ['name' => $child],
                    ['parent_id' => $parentCategory->id, 'is_active' => true]
                );
            }
        }
    }
}
