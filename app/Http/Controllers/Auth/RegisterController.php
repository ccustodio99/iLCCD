<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'contact_info' => 'nullable|string|max:255',
            'password' => ['required', 'confirmed', 'min:8', 'regex:/[A-Za-z]/', 'regex:/[0-9]/', 'regex:/[^A-Za-z0-9]/'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'contact_info' => $data['contact_info'] ?? null,
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);

        return redirect('/');
    }
}
