<?php

use App\Models\JobOrder;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('allows owner to download job order attachment', function () {
    Storage::fake('public');

    $owner = User::factory()->create();
    $path = UploadedFile::fake()->create('file.txt', 1)->store('job_order_attachments', 'public');
    $order = JobOrder::factory()->for($owner)->create(['attachment_path' => $path]);

    $this->actingAs($owner);
    $this->get("/job-orders/{$order->id}/attachment")->assertOk()->assertDownload(basename($path));
});

it('prevents others from downloading job order attachment', function () {
    Storage::fake('public');

    $owner = User::factory()->create();
    $other = User::factory()->create();
    $path = UploadedFile::fake()->create('file.txt', 1)->store('job_order_attachments', 'public');
    $order = JobOrder::factory()->for($owner)->create(['attachment_path' => $path]);

    $this->actingAs($other);
    $this->get("/job-orders/{$order->id}/attachment")->assertForbidden();
});
