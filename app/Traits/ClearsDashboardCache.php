<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

/**
 * Trait to flush dashboard caches when a model is created, updated, or deleted.
 */
trait ClearsDashboardCache
{
    public static function bootClearsDashboardCache(): void
    {
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(function (): void {
                Cache::flush();
            });
        }
    }
}
