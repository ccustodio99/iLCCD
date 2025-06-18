<?php

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('replaces existing logo when uploading new file', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['role' => 'admin']);
    $old = UploadedFile::fake()->image('old.jpg')->store('branding', 'public');
    Setting::set('logo_path', 'storage/'.$old);
    $this->actingAs($admin);

    $response = $this->put('/settings/branding', [
        'logo' => UploadedFile::fake()->image('new.jpg'),
    ]);

    $response->assertRedirect('/settings/branding');
    Storage::disk('public')->assertMissing($old);
    $newPath = str_replace('storage/', '', Setting::get('logo_path'));
    Storage::disk('public')->assertExists($newPath);
});

it('replaces existing favicon when uploading new file', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['role' => 'admin']);
    $old = UploadedFile::fake()->image('old.png')->store('branding', 'public');
    Setting::set('favicon_path', 'storage/'.$old);
    $this->actingAs($admin);

    $response = $this->put('/settings/branding', [
        'favicon' => UploadedFile::fake()->image('new.png'),
    ]);

    $response->assertRedirect('/settings/branding');
    Storage::disk('public')->assertMissing($old);
    $newPath = str_replace('storage/', '', Setting::get('favicon_path'));
    Storage::disk('public')->assertExists($newPath);
});
