<?php

namespace App\Http\Middleware;

use App\Models\License;
use Closure;

class CheckLicense
{
    protected static ?bool $hasTable = null;

    public function handle($request, Closure $next)
    {
        if ($request->is('license', 'license/*')) {
            return $next($request);
        }


        if (self::$hasTable === null) {
            self::$hasTable = Schema::hasTable('licenses');
        }

        if (! self::$hasTable) {
            return $next($request);
        }

        $license = License::current();

        if (! $license || ! $license->isValid()) {
            return redirect()
                ->route('license.index')
                ->withErrors(['license' => 'License expired or invalid.']);
        }

        return $next($request);
    }
}
