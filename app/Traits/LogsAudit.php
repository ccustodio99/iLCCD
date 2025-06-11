<?php

namespace App\Traits;

use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;

/**
 * Trait to automatically log model events to the audit trail.
 */
trait LogsAudit
{
    public static function bootLogsAudit(): void
    {
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordAudit($event);
            });
        }
    }

    /**
     * Create an audit trail entry for the given action.
     */
    protected function recordAudit(string $action): void
    {
        if (! Auth::check()) {
            return;
        }

        AuditTrail::create([
            'auditable_id' => $this->getKey(),
            'auditable_type' => static::class,
            'user_id' => Auth::id(),
            'action' => $action,
        ]);
    }
}
