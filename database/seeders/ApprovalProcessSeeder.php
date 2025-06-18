<?php

namespace Database\Seeders;

use App\Models\ApprovalProcess;
use App\Models\JobOrder;
use App\Models\Requisition;
use Illuminate\Database\Seeder;

class ApprovalProcessSeeder extends Seeder
{
    /**
     * Seed default approval processes for common modules.
     */
    public function run(): void
    {
        $departments = ['ITRC', 'Nursing', 'CCS'];

        foreach ($departments as $department) {
            // Requisition workflow
            $reqProcess = ApprovalProcess::firstOrCreate([
                'module' => 'requisitions',
                'department' => $department,
            ]);

            $reqProcess->stages()->firstOrCreate([
                'name' => Requisition::STATUS_PENDING_HEAD,
                'position' => 1,
            ]);
            $reqProcess->stages()->firstOrCreate([
                'name' => Requisition::STATUS_PENDING_PRESIDENT,
                'position' => 2,
            ]);
            $reqProcess->stages()->firstOrCreate([
                'name' => Requisition::STATUS_PENDING_FINANCE,
                'position' => 3,
            ]);

            // Job order workflow
            $jobProcess = ApprovalProcess::firstOrCreate([
                'module' => 'job_orders',
                'department' => $department,
            ]);

            $jobProcess->stages()->firstOrCreate([
                'name' => JobOrder::STATUS_PENDING_HEAD,
                'position' => 1,
            ]);
            $jobProcess->stages()->firstOrCreate([
                'name' => JobOrder::STATUS_PENDING_PRESIDENT,
                'position' => 2,
            ]);
            $jobProcess->stages()->firstOrCreate([
                'name' => JobOrder::STATUS_PENDING_FINANCE,
                'position' => 3,
            ]);
        }
    }
}
