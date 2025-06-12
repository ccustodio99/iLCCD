<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);
        $users = User::paginate($perPage)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = User::ROLES;
        return view('users.create', compact('roles'));
    }

    public function edit(User $user)
    {
        $roles = User::ROLES;
        return view('users.edit', compact('user', 'roles'));
    }

    public function store(Request $request)
    {
        $roles = User::ROLES;

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => ['required', 'confirmed', 'min:8', 'regex:/[A-Za-z]/', 'regex:/[0-9]/', 'regex:/[^A-Za-z0-9]/'],
            'role' => 'required|in:' . implode(',', $roles),
            'department' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'department' => $data['department'] ?? null,
            'is_active' => $data['is_active'] ?? false,
        ]);

        return redirect()->route('users.index');
    }

    public function update(Request $request, User $user)
    {
        $roles = User::ROLES;

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', 'min:8', 'regex:/[A-Za-z]/', 'regex:/[0-9]/', 'regex:/[^A-Za-z0-9]/'],
            'role' => 'required|in:' . implode(',', $roles),
            'department' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $roleChanged = $user->role !== $data['role'];

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'];
        $user->department = $data['department'] ?? null;
        $user->is_active = $data['is_active'] ?? false;
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        if ($roleChanged) {
            if (Auth::id() === $user->id) {
                Auth::logoutOtherDevices($data['password'] ?? '');
                $request->session()->regenerate();
            }
        }

        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index');
    }
}
