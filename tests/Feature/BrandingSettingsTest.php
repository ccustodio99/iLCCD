<?php

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('replaces old logo when uploading new one', function () {
    Storage::fake('public');
    Setting::set('logo_path', 'storage/'.UploadedFile::fake()->image('oldlogo.png')->store('branding', 'public'));
    $oldPath = setting('logo_path');

    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->put('/settings/branding', [
        'logo' => UploadedFile::fake()->image('newlogo.png'),
    ]);

    $response->assertRedirect('/settings/branding');
    Storage::disk('public')->assertMissing(str_replace('storage/', '', $oldPath));
    $newPath = setting('logo_path');
    expect($newPath)->not()->toBe($oldPath);
    Storage::disk('public')->assertExists(str_replace('storage/', '', $newPath));
});

it('replaces old favicon when uploading new one', function () {
    Storage::fake('public');
    Setting::set('favicon_path', 'storage/'.UploadedFile::fake()->image('oldicon.png')->store('branding', 'public'));
    $oldPath = setting('favicon_path');

    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->put('/settings/branding', [
        'favicon' => UploadedFile::fake()->image('newicon.png'),
    ]);

    $response->assertRedirect('/settings/branding');
    Storage::disk('public')->assertMissing(str_replace('storage/', '', $oldPath));
    $newPath = setting('favicon_path');
    expect($newPath)->not()->toBe($oldPath);
    Storage::disk('public')->assertExists(str_replace('storage/', '', $newPath));
});

it('handles old logo path without storage prefix', function () {
    Storage::fake('public');
    $stored = UploadedFile::fake()->image('oldlogo.png')->store('branding', 'public');
    Setting::set('logo_path', $stored);
    $oldPath = setting('logo_path');

    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->put('/settings/branding', [
        'logo' => UploadedFile::fake()->image('newlogo.png'),
    ]);

    $response->assertRedirect('/settings/branding');
    Storage::disk('public')->assertMissing($oldPath);
    $newPath = setting('logo_path');
    expect($newPath)->not()->toBe($oldPath);
    Storage::disk('public')->assertExists(str_replace('storage/', '', $newPath));
});

it('handles old favicon path without storage prefix', function () {
    Storage::fake('public');
    $stored = UploadedFile::fake()->image('oldicon.png')->store('branding', 'public');
    Setting::set('favicon_path', $stored);
    $oldPath = setting('favicon_path');

    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->put('/settings/branding', [
        'favicon' => UploadedFile::fake()->image('newicon.png'),
    ]);

    $response->assertRedirect('/settings/branding');
    Storage::disk('public')->assertMissing($oldPath);
    $newPath = setting('favicon_path');
    expect($newPath)->not()->toBe($oldPath);
    Storage::disk('public')->assertExists(str_replace('storage/', '', $newPath));
});

it('resizes logo to 300px width', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->put('/settings/branding', [
        'logo' => UploadedFile::fake()->image('wide.png', 600, 400),
    ]);

    $response->assertRedirect('/settings/branding');
    $path = str_replace('storage/', '', setting('logo_path'));
    $file = Storage::disk('public')->path($path);
    [$width] = getimagesize($file);
    expect($width)->toBe(300);
});

it('resizes favicon to 32px width', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->put('/settings/branding', [
        'favicon' => UploadedFile::fake()->image('icon.png', 100, 100),
    ]);

    $response->assertRedirect('/settings/branding');
    $path = str_replace('storage/', '', setting('favicon_path'));
    $file = Storage::disk('public')->path($path);
    [$width] = getimagesize($file);
    expect($width)->toBe(32);
});
