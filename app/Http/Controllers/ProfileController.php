<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'contact_info' => ['nullable', 'string', 'max:255', 'regex:/^\+?[0-9\s\-\(\)]{7,20}$/'],
            'profile_photo' => 'nullable|image|max:2048',
            'password' => ['nullable', 'confirmed', 'min:8', 'regex:/[A-Za-z]/', 'regex:/[0-9]/', 'regex:/[^A-Za-z0-9]/'],
            'remove_photo' => 'boolean',
        ], [
            'contact_info.regex' => 'Contact information must be a valid phone number.',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->contact_info = $data['contact_info'] ?? null;
        $removePhoto = $data['remove_photo'] ?? false;

        if ($request->hasFile('profile_photo')) {
            $oldPath = $user->profile_photo_path;
            try {
                if ($oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
                $user->profile_photo_path = $request->file('profile_photo')->store('profile_photos', 'public');
            } catch (\Throwable $e) {
                Log::error(
                    'Failed to replace profile photo for user '.$user->id.': '.$e->getMessage(),
                    ['exception' => $e]
                );
                $user->profile_photo_path = $oldPath;

                return redirect()->back()
                    ->withErrors('Unable to upload new profile photo. Please try again later.')
                    ->with('error', 'Unable to upload new profile photo. Please try again later.');
            }
        } elseif ($removePhoto && $user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
            $user->profile_photo_path = null;
        }
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        if (! empty($data['password'])) {
            Auth::logoutOtherDevices($data['password']);
            $request->session()->regenerate();
        }

        return redirect()->route('profile.edit')
            ->with('success', 'Profile updated successfully.');
    }
}
