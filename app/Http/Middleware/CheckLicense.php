<?php

namespace App\Http\Middleware;

use App\Models\License;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class CheckLicense
{
    public function handle($request, Closure $next)
    {
        if ($request->routeIs('license.index', 'license.activate', 'license.renew')) {
            return $next($request);
        }

        $license = License::current();

        if (! $license || ! $license->isValid()) {
            abort(Response::HTTP_FORBIDDEN, 'License expired or invalid.');
        }

        return $next($request);
    }
}
