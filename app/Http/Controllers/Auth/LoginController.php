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

        if (Auth::attempt($credentials, $remember)) {
            if (!Auth::user()->is_active) {
                $user = Auth::user();
                AuditTrail::create([
                    'auditable_id' => $user->id,
                    'auditable_type' => User::class,
                    'user_id' => $user->id,
                    'action' => 'login_failed',
                ]);

                Auth::logout();

                return back()->withErrors([
                    'email' => 'Account disabled.',
                ])->onlyInput('email');
            }

            $user = Auth::user();

            AuditTrail::create([
                'auditable_id' => $user->id,
                'auditable_type' => User::class,
                'user_id' => $user->id,
                'action' => 'login',
            ]);

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        $user = User::where('email', $request->input('email'))->first();

        if ($user) {
            AuditTrail::create([
                'auditable_id' => $user->id,
                'auditable_type' => User::class,
                'user_id' => $user->id,
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
                'action' => 'logout',
            ]);
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
