<?php

namespace Database\Seeders;

use App\Models\ApprovalProcess;
use App\Models\JobOrder;
use App\Models\Requisition;
use App\Models\User;
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
            $head = User::where('department', $department)->where('role', 'head')->first();
            $financeHead = User::where('department', 'Finance Office')->where('role', 'head')->first();
            $president = User::where('department', 'President Department')->where('role', 'head')->first();

            // Requisition workflow
            $reqProcess = ApprovalProcess::firstOrCreate([
                'module' => 'requisitions',
                'department' => $department,
            ]);

            $reqProcess->stages()->firstOrCreate([
                'name' => Requisition::STATUS_PENDING_HEAD,
                'position' => 1,
                'assigned_user_id' => $head?->id,
            ]);
            $reqProcess->stages()->firstOrCreate([
                'name' => Requisition::STATUS_PENDING_PRESIDENT,
                'position' => 2,
                'assigned_user_id' => $president?->id,
            ]);
            $reqProcess->stages()->firstOrCreate([
                'name' => Requisition::STATUS_PENDING_FINANCE,
                'position' => 3,
                'assigned_user_id' => $financeHead?->id,
            ]);

            // Job order workflow
            $jobProcess = ApprovalProcess::firstOrCreate([
                'module' => 'job_orders',
                'department' => $department,
            ]);

            $jobProcess->stages()->firstOrCreate([
                'name' => JobOrder::STATUS_PENDING_HEAD,
                'position' => 1,
                'assigned_user_id' => $head?->id,
            ]);
            $jobProcess->stages()->firstOrCreate([
                'name' => JobOrder::STATUS_PENDING_PRESIDENT,
                'position' => 2,
                'assigned_user_id' => $president?->id,
            ]);
            $jobProcess->stages()->firstOrCreate([
                'name' => JobOrder::STATUS_PENDING_FINANCE,
                'position' => 3,
                'assigned_user_id' => $financeHead?->id,
            ]);
        }
    }
}
