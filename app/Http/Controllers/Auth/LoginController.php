<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditTrail;
use App\Models\User;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        $user = User::where('email', $credentials['email'])->first();

        if ($user && $user->lockout_until && $user->lockout_until->isFuture()) {
            return back()->withErrors([
                'email' => 'Account locked. Please try again later.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $remember)) {
            if (!Auth::user()->is_active) {
                $user = Auth::user();
                AuditTrail::create([
                    'auditable_id' => $user->id,
                    'auditable_type' => User::class,
                    'user_id' => $user->id,
                    'ip_address' => $request->ip(),
                    'action' => 'login_failed',
                ]);

                Auth::logout();

                return back()->withErrors([
                    'email' => 'Account disabled.',
                ])->onlyInput('email');
            }

            $user = Auth::user();

            $user->update([
                'failed_login_attempts' => 0,
                'lockout_until' => null,
            ]);

            AuditTrail::create([
                'auditable_id' => $user->id,
                'auditable_type' => User::class,
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'action' => 'login',
            ]);

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        $user = $user ?? User::where('email', $request->input('email'))->first();

        if ($user) {
            $user->failed_login_attempts++;

            if ($user->failed_login_attempts >= 5) {
                $user->lockout_until = now()->addMinutes(15);
                AuditTrail::create([
                    'auditable_id' => $user->id,
                    'auditable_type' => User::class,
                    'user_id' => $user->id,
                    'ip_address' => $request->ip(),
                    'action' => 'account_locked',
                ]);
            }

            $user->save();

            AuditTrail::create([
                'auditable_id' => $user->id,
                'auditable_type' => User::class,
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'action' => 'login_failed',
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $userId = Auth::id();

        Auth::logout();

        if ($userId) {
            AuditTrail::create([
                'auditable_id' => $userId,
                'auditable_type' => User::class,
                'user_id' => $userId,
                'ip_address' => $request->ip(),
                'action' => 'logout',
            ]);
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
