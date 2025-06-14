<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('allows admin to view user list', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);
    $response = $this->get('/users');
    $response->assertStatus(200);
});

it('allows admin to create a user', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->post('/users', [
        'name' => 'New User',
        'email' => 'new@example.com',
        'password' => 'Password1!',
        'password_confirmation' => 'Password1!',
        'role' => 'staff',
        'department' => 'CCS',
        'is_active' => true,
    ]);

    $response->assertRedirect('/users');
    expect(User::where('email', 'new@example.com')->exists())->toBeTrue();
});

it('allows admin to create users with every role', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    foreach (User::ROLES as $role) {
        $email = $role.'@example.com';
        $response = $this->post('/users', [
            'name' => 'Role '.$role,
            'email' => $email,
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
            'role' => $role,
            'department' => 'Demo',
            'is_active' => true,
        ]);

        $response->assertRedirect('/users');
        expect(User::where('email', $email)->where('role', $role)->exists())->toBeTrue();
    }
});

it('allows admin to edit a user', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create();
    $this->actingAs($admin);
    $response = $this->put("/users/{$user->id}", [
        'name' => 'Updated',
        'email' => 'updated@example.com',
        'password' => 'Newpassword1!',
        'password_confirmation' => 'Newpassword1!',
        'role' => 'admin',
        'department' => 'ITRC',
        'is_active' => true,
    ]);
    $response->assertRedirect('/users');
    expect($user->fresh()->name)->toBe('Updated');
});


it('rejects invalid role during update', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create();
    $this->actingAs($admin);
    $response = $this->from("/users/{$user->id}/edit")->put("/users/{$user->id}", [
        'name' => 'Test',
        'email' => 'test@example.com',
        'role' => 'invalid',
    ]);
    $response->assertSessionHasErrors('role');
});

it('enforces password complexity during creation', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->from('/users/create')->post('/users', [
        'name' => 'Bad Password',
        'email' => 'bad@example.com',
        'password' => 'simple',
        'password_confirmation' => 'simple',
        'role' => 'staff',
    ]);

    $response->assertSessionHasErrors('password');
});


it('prevents login for inactive users', function () {
    $user = User::factory()->create(['is_active' => false]);
    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);
    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

it('replaces old profile photo when admin updates user', function () {
    Storage::fake('public');

    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create([
        'profile_photo_path' => UploadedFile::fake()->image('old.jpg')->store('profile_photos', 'public'),
    ]);
    $oldPath = $user->profile_photo_path;
    $this->actingAs($admin);

    $response = $this->put("/users/{$user->id}", [
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role,
        'is_active' => true,
        'profile_photo' => UploadedFile::fake()->image('new.jpg'),
    ]);

    $response->assertRedirect('/users');
    Storage::disk('public')->assertMissing($oldPath);
    $user->refresh();
    Storage::disk('public')->assertExists($user->profile_photo_path);
});

it('deletes profile photo when admin deletes user', function () {
    Storage::fake('public');

    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create([
        'profile_photo_path' => UploadedFile::fake()->image('photo.jpg')->store('profile_photos', 'public'),
    ]);
    $path = $user->profile_photo_path;

    $this->actingAs($admin);
    $response = $this->delete("/users/{$user->id}");

    $response->assertRedirect('/users');
    Storage::disk('public')->assertMissing($path);
    expect(User::find($user->id))->toBeNull();
});
