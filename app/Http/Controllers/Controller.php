<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

abstract class Controller extends BaseController
{
    /**
     * Validate and return the per-page value for pagination.
     */
    protected function getPerPage(Request $request, int $default = 10): int
    {
        $perPage = $request->integer('per_page', $default);
        $allowed = [5, 10, 20, 50];

        return in_array($perPage, $allowed, true) ? $perPage : $default;
    }
}
