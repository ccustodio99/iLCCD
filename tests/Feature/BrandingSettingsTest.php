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
