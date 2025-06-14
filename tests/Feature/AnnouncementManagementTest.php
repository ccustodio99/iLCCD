<?php
use App\Models\Announcement;
use App\Models\User;

it('allows admin to create announcement', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->post('/settings/announcements', [
        'title' => 'Holiday',
        'content' => 'No classes',
        'is_active' => true,
    ]);

    $response->assertRedirect('/settings/announcements');
    expect(Announcement::where('title', 'Holiday')->exists())->toBeTrue();
});

it('allows admin to update announcement', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $announcement = Announcement::create([
        'title' => 'Holiday',
        'content' => 'No classes',
        'is_active' => true,
    ]);
    $this->actingAs($admin);

    $response = $this->put("/settings/announcements/{$announcement->id}", [
        'title' => 'Updated',
        'content' => 'Schedule change',
        'is_active' => true,
    ]);

    $response->assertRedirect('/settings/announcements');
    expect($announcement->fresh()->title)->toBe('Updated');
});

it('allows admin to delete announcement', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $announcement = Announcement::create([
        'title' => 'Holiday',
        'content' => 'No classes',
        'is_active' => true,
    ]);
    $this->actingAs($admin);

    $response = $this->delete("/settings/announcements/{$announcement->id}");

    $response->assertRedirect('/settings/announcements');
    $archived = Announcement::withTrashed()->find($announcement->id);
    expect($archived->archived_at)->not->toBeNull();
});
