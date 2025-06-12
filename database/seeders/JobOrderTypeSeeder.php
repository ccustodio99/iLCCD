<?php

namespace Database\Seeders;

use App\Models\JobOrderType;
use Illuminate\Database\Seeder;

class JobOrderTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Installation & Deployment',
            'IT Equipment Setup (Computers, Printers, Network Devices)',
            'Software Deployment (LMS, SIS Modules, Business Apps)',
            'Classroom AV Installation (Projectors, Smartboards)',
            'Maintenance',
            'Preventative Maintenance',
            'HVAC System Checks',
            'Electrical Safety Inspections',
            'Vehicle & Gym Equipment Servicing',
            'Corrective Maintenance',
            'Repair Broken Fixtures (Doors, Furniture)',
            'Fix Plumbing Leaks & Clogs',
            'Inspection & Audit',
            'Safety & Compliance Audits (Fire Extinguishers, Alarms)',
            'KPI & Audit Log Reviews',
            'Inventory Spot-Checks',
            'Emergency Response',
            'Power Outages & Electrical Failures',
            'Urgent Plumbing Leaks',
            'Critical Network/Server Downtime',
            'Upgrades & Updates',
            'Hardware Upgrades (RAM, Storage)',
            'Network Firmware & Switch Upgrades',
            'Software Patching & Version Updates',
            'Calibration & Testing',
            'Lab Equipment Calibration',
            'Sensor & Instrument Testing',
            'Scanner / Printer Accuracy Checks',
            'Decommissioning & Removal',
            'Equipment Decommission (Old PCs, AV Gear)',
            'Furniture Removal & Disposal',
            'Cleaning & Housekeeping',
            'Deep Cleaning Requests (Labs, Classrooms)',
            'Grounds & Landscaping Jobs',
            'Other Job Request',
            'Anything else—our triage team will route it appropriately.',
        ];
        foreach ($types as $name) {
            JobOrderType::create([
                'name' => $name,
                'is_active' => true,
            ]);
        }
    }
}
