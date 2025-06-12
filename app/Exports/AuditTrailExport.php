<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AuditTrailExport implements FromCollection, WithHeadings
{
    protected Collection $logs;

    public function __construct(Collection $logs)
    {
        $this->logs = $logs;
    }

    public function collection(): Collection
    {
        return $this->logs->map(function ($log) {
            return [
                'id' => $log->id,
                'user' => optional($log->user)->name,
                'action' => $log->action,
                'auditable_type' => $log->auditable_type,
                'auditable_id' => $log->auditable_id,
                'ip_address' => $log->ip_address,
                'created_at' => $log->created_at->toDateTimeString(),
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'User', 'Action', 'Type', 'Record ID', 'IP Address', 'Created At'];
    }
}
