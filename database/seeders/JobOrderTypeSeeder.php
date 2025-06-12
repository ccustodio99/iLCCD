<?php

namespace Database\Seeders;

use App\Models\JobOrderType;
use Illuminate\Database\Seeder;

class JobOrderTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Installation & Deployment' => [
                'IT Equipment Setup (matches Computers & Devices)',
                'Software Deployment (matches Software & Apps)',
                'Classroom AV Installation (matches Classroom AV)',
            ],
            'Maintenance' => [
                'Preventative Maintenance (links Facilities & Maintenance)',
                'Corrective Repairs (Doors, Plumbing, HVAC)',
            ],
            'Inspection & Audit' => [
                'Safety & Compliance Audits (e.g. Fire Extinguisher Tests)',
                'Inventory Spot-Checks (cross-links Inventory Management)',
            ],
            'Emergency Response' => [
                'Power Outages (matches Electrical Outages)',
                'Critical Network/Server Downtime (matches Network Outages)',
            ],
            'Upgrades & Updates' => [
                'Hardware Upgrades (RAM, Storage)',
                'Software Patching & Version Updates',
            ],
            'Calibration & Testing' => [
                'Lab Equipment Calibration (links Laboratory Equipment)',
                'Printer/Scanner Accuracy Checks (cross-links Printing & Scanning)',
            ],
            'Decommissioning & Removal' => [],
            'Cleaning & Housekeeping' => [],
            'Other Job Request' => [],
        ];

        foreach ($types as $parent => $children) {
            $parentType = JobOrderType::create([
                'name' => $parent,
                'is_active' => true,
            ]);

            foreach ($children as $child) {
                JobOrderType::create([
                    'name' => $child,
                    'parent_id' => $parentType->id,
                    'is_active' => true,
                ]);
            }
        }
    }
}
