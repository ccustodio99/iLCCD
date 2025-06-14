<?php
use App\Models\DocumentCategory;
use App\Models\User;

it('allows admin to create document category', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->post('/settings/document-categories', [
        'name' => 'Policies',
        'is_active' => true,
    ]);

    $response->assertRedirect('/settings/document-categories');
    expect(DocumentCategory::where('name', 'Policies')->exists())->toBeTrue();
});

it('allows admin to update document category', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = DocumentCategory::factory()->create(['name' => 'Policies']);
    $this->actingAs($admin);

    $response = $this->put("/settings/document-categories/{$category->id}", [
        'name' => 'Procedures',
        'is_active' => true,
    ]);

    $response->assertRedirect('/settings/document-categories');
    expect($category->fresh()->name)->toBe('Procedures');
});

it('allows admin to delete document category', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = DocumentCategory::factory()->create();
    $this->actingAs($admin);

    $response = $this->delete("/settings/document-categories/{$category->id}");

    $response->assertRedirect('/settings/document-categories');
    expect(DocumentCategory::where('id', $category->id)->exists())->toBeFalse();
});
