<?php

namespace App\Http\Controllers;

use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class LicenseController extends Controller
{
    public function index()
    {
        if (! Schema::hasTable('licenses')) {
            return view('license.index', ['license' => null])
                ->withErrors(['license' => 'License table missing. Please run migrations.']);
        }

        $license = License::current();

        return view('license.index', ['license' => $license]);
    }

    public function activate(Request $request)
    {
        $encoded = $request->input('license_text')
            ?? $request->input('license');

        if (! $encoded && $request->hasFile('license_file')) {
            $encoded = $request->file('license_file')->get();
        }

        $encoded = trim((string) $encoded);

        $request->merge(['license' => $encoded]);
        $request->validate(['license' => 'required|string']);

        if (! $this->storeLicense($encoded)) {
            return back()->withErrors(['license' => 'Invalid license.']);
        }

        return back()->with('status', 'License activated');
    }

    public function renew(Request $request)
    {
        return $this->activate($request);
    }

    public function destroy()
    {
        $license = License::current();
        if ($license) {
            $license->update(['active' => false]);
        }

        return back()->with('status', 'License removed');
    }

    public function manage()
    {
        $licenses = License::orderByDesc('created_at')->get();

        return view('license.manage', ['licenses' => $licenses]);
    }

    /**
     * Validate and store a license string.
     */
    protected function storeLicense(string $encoded): bool
    {
        $raw = base64_decode($encoded, true);
        if (! $raw) {
            return false;
        }

        $parts = explode('|', $raw);
        if (count($parts) !== 3) {
            return false;
        }

        [$key, $timestamp, $signature] = $parts;

        $expected = hash_hmac('sha256', "{$key}|{$timestamp}", config('license.secret'));
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
