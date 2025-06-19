<?php

use App\Models\User;
use Illuminate\Auth\Events\OtherDeviceLogout;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

it('allows user to view profile page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = $this->get('/profile');
    $response->assertStatus(200);
    $response->assertSee('My Profile');
});

it('allows user to update profile', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = $this->put('/profile', [
        'name' => 'Updated User',
        'email' => 'updated@example.com',
        'contact_info' => '09171234567',
        'password' => 'Newpassword1!',
        'password_confirmation' => 'Newpassword1!',
    ]);
    $response->assertRedirect('/profile');
    $response->assertSessionHas('success', 'Profile updated successfully.');
    $user->refresh();
    expect($user->name)->toBe('Updated User')
        ->and($user->contact_info)->toBe('09171234567')
        ->and(Hash::check('Newpassword1!', $user->password))->toBeTrue();
});

it('does not change password when not provided', function () {
    $user = User::factory()->create();
    $oldHash = $user->password;
    $this->actingAs($user);

    $response = $this->put('/profile', [
        'name' => 'Updated User',
        'email' => 'updated@example.com',
        'contact_info' => '09171234567',
    ]);

    $response->assertRedirect('/profile');
    $response->assertSessionHas('success', 'Profile updated successfully.');
    $user->refresh();
    expect($user->password)->toBe($oldHash);
});

it('replaces old profile photo when updating', function () {
    Storage::fake('public');
    $user = User::factory()->create([
        'profile_photo_path' => UploadedFile::fake()->image('old.jpg')->store('profile_photos', 'public'),
    ]);
    $oldPath = $user->profile_photo_path;
    $this->actingAs($user);

    $response = $this->put('/profile', [
        'name' => $user->name,
        'email' => $user->email,
        'profile_photo' => UploadedFile::fake()->image('new.jpg'),
    ]);

    $response->assertRedirect('/profile');
    Storage::disk('public')->assertMissing($oldPath);
    $user->refresh();
    Storage::disk('public')->assertExists($user->profile_photo_path);
});

it('deletes profile photo when remove flag is used', function () {
    Storage::fake('public');
    $user = User::factory()->create([
        'profile_photo_path' => UploadedFile::fake()->image('photo.jpg')->store('profile_photos', 'public'),
    ]);
    $path = $user->profile_photo_path;
    $this->actingAs($user);

    $this->put('/profile', [
        'name' => $user->name,
        'email' => $user->email,
        'remove_photo' => true,
    ])->assertRedirect('/profile');

    Storage::disk('public')->assertMissing($path);
    $user->refresh();
    expect($user->profile_photo_path)->toBeNull();
});

it('redirects guest to login when viewing profile', function () {
    $response = $this->get('/profile');

    $response->assertRedirect('/login');
});

it('redirects guest to login when updating profile', function () {
    $response = $this->put('/profile');

    $response->assertRedirect('/login');
});

it('invalidates old sessions when password is updated', function () {
    Event::fake();

    $user = User::factory()->create(['password' => Hash::make('Password1!')]);
    $this->actingAs($user);

    $this->put('/profile', [
        'name' => $user->name,
        'email' => $user->email,
        'password' => 'NewPassword1!',
        'password_confirmation' => 'NewPassword1!',
    ])->assertRedirect('/profile');

    Event::assertDispatched(OtherDeviceLogout::class);
});
