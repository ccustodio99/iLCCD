<?php

use App\Models\JobOrder;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('stores attachment when creating job order', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post('/job-orders', [
        'job_type' => 'Repair',
        'description' => 'Fix',
        'attachment' => UploadedFile::fake()->create('test.txt', 10),
    ]);

    $response->assertRedirect('/job-orders');
    $order = JobOrder::first();
    Storage::disk('public')->assertExists($order->attachment_path);
});
