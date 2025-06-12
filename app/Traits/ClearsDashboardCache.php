<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Cache\TaggableStore;
use Illuminate\Cache\ArrayStore;

/**
 * Trait to flush dashboard caches when a model is created, updated, or deleted.
 */
trait ClearsDashboardCache
{
    public static function bootClearsDashboardCache(): void
    {
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(function (): void {
                self::clearDashboardCache();
            });
        }
    }

    protected static function clearDashboardCache(): void
    {
        $store = Cache::getStore();

        if ($store instanceof TaggableStore) {
            Cache::tags('dashboard')->flush();

            return;
        }

        $prefix = method_exists($store, 'getPrefix') ? $store->getPrefix() : config('cache.prefix');

        if (config('cache.default') === 'database') {
            $connection = config('cache.stores.database.connection');
            $table = config('cache.stores.database.table', 'cache');

            DB::connection($connection)
                ->table($table)
                ->where('key', 'like', $prefix.'dashboard:%')
                ->delete();

            return;
        }

        if ($store instanceof ArrayStore) {
            $reflection = new \ReflectionClass($store);
            $property = $reflection->getProperty('storage');
            $property->setAccessible(true);
            $storage = $property->getValue($store);

            foreach (array_keys($storage) as $key) {
                if (str_starts_with($key, $prefix.'dashboard:')) {
                    Cache::forget($key);
                }
            }

            return;
        }

        Cache::flush();
    }
}
