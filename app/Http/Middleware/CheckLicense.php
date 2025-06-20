<?php

namespace App\Http\Middleware;

use App\Models\License;
use Closure;

class CheckLicense
{
    public function handle($request, Closure $next)
    {
        if ($request->is('license', 'license/*')) {
            return $next($request);
        }

        if (! license_table_exists()) {
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
