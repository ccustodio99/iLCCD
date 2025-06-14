<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);

        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->filled('department')) {
            $query->where('department', $request->input('department'));
        }

        if ($request->filled('status')) {
            $active = $request->input('status') === 'active';
            $query->where('is_active', $active);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate($perPage)->withQueryString();

        $roles = User::ROLES;
        $departments = User::select('department')
            ->distinct()
            ->whereNotNull('department')
            ->orderBy('department')
            ->pluck('department');

        return view('users.index', compact('users', 'roles', 'departments'));
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
            'contact_info' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'department' => $data['department'] ?? null,
            'contact_info' => $data['contact_info'] ?? null,
            'is_active' => $data['is_active'] ?? false,
        ];
        if ($request->hasFile('profile_photo')) {
            $payload['profile_photo_path'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        User::create($payload);

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
            'contact_info' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $roleChanged = $user->role !== $data['role'];

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'];
        $user->department = $data['department'] ?? null;
        $user->contact_info = $data['contact_info'] ?? null;
        $user->is_active = $data['is_active'] ?? false;
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $user->profile_photo_path = $request->file('profile_photo')
                ->store('profile_photos', 'public');
        }
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
