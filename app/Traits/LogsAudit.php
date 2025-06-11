<?php

namespace App\Traits;

use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

        $changes = null;

        if ($action === 'updated') {
            $diff = collect($this->getChanges())
                ->except(['updated_at'])
                ->mapWithKeys(function ($value, $field) {
                    return [
                        $field => [
                            'old' => $this->getOriginal($field),
                            'new' => $value,
                        ],
                    ];
                })
                ->toArray();

            if (! empty($diff)) {
                $changes = $diff;
            } else {
                return; // only timestamp changed
            }
        }

        AuditTrail::create([
            'auditable_id' => $this->getKey(),
            'auditable_type' => static::class,
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'action' => $action,
            'changes' => $changes,
        ]);
    }

    /**
     * Access the audit trail entries for this model.
     */
    public function auditTrails(): MorphMany
    {
        return $this->morphMany(AuditTrail::class, 'auditable');
    }
}
