<?php

namespace Database\Seeders;

use App\Models\JobOrderType;
use Illuminate\Database\Seeder;

class JobOrderTypeSeeder extends Seeder
{
    /**
     * Default job order type hierarchy.
     *
     * @var array<string, array<int, string>>
     */
    private const TYPES = [
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

    /**
     * Flattened list of all canonical names.
     */
    public static function allNames(): array
    {
        return collect(self::TYPES)
            ->flatMap(fn ($children, $parent) => array_merge([$parent], $children))
            ->all();
    }

    public function run(): void
    {
        JobOrderType::whereNotIn('name', self::allNames())->delete();

        foreach (self::TYPES as $parent => $children) {
            $parentType = JobOrderType::firstOrCreate(
                ['name' => $parent],
                ['is_active' => true]
            );

            foreach ($children as $child) {
                JobOrderType::firstOrCreate(
                    ['name' => $child],
                    ['parent_id' => $parentType->id, 'is_active' => true]
                );
            }
        }
    }
}
