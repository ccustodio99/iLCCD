<?php

namespace App\Http\Controllers;

use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LicenseController extends Controller
{
    public function index()
    {
        $license = License::current();

        return view('license.index', ['license' => $license]);
    }

    public function activate(Request $request)
    {
        $request->validate(['license' => 'required|string']);

        if (! $this->storeLicense($request->input('license'))) {
            return back()->withErrors(['license' => 'Invalid license.']);
        }

        return back()->with('status', 'License activated');
    }

    public function renew(Request $request)
    {
        return $this->activate($request);
    }

    public function manage()
    {
        $licenses = License::orderByDesc('created_at')->get();

        return view('license.manage', ['licenses' => $licenses]);
    }

    protected function storeLicense(string $encoded): bool
    {
        $raw = base64_decode($encoded, true);
        if (! $raw) {
            return false;
        }

        [$key, $timestamp, $signature] = explode('|', $raw);

        $expected = hash_hmac('sha256', "{$key}|{$timestamp}", config('app.key'));
        if (! hash_equals($expected, $signature)) {
            return false;
        }

        $expires = Carbon::createFromTimestamp((int) $timestamp);
        if ($expires->isPast()) {
            return false;
        }

        License::query()->update(['active' => false]);
        License::updateOrCreate(
            ['key' => $key],
            [
                'signature' => $signature,
                'expires_at' => $expires,
                'active' => true,
            ]
        );

        return true;
    }
}
