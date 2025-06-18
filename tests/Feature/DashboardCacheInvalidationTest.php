<?php

use App\Models\Announcement;
use App\Models\DocumentLog;
use App\Models\JobOrder;
use App\Models\PurchaseOrder;
use App\Models\Requisition;
use App\Models\Ticket;
use Illuminate\Support\Facades\Cache;

// Dataset of models and update attributes
\dataset('dashboardModels', [
    [Announcement::class, ['title' => 'changed']],
    [Ticket::class, ['subject' => 'changed']],
    [JobOrder::class, ['description' => 'changed']],
    [Requisition::class, ['purpose' => 'changed']],
    [PurchaseOrder::class, ['item' => 'changed']],
    [DocumentLog::class, ['action' => 'changed']],
]);

it('clears dashboard cache when contributing models are updated', function (string $class, array $changes) {
    Cache::put('dashboard:test', 'foo');

    if ($class === Announcement::class) {
        $model = Announcement::create([
            'title' => 'init',
            'content' => 'content',
            'is_active' => true,
        ]);
    } else {
        $model = $class::factory()->create();
    }

    $model->update($changes);

    expect(Cache::has('dashboard:test'))->toBeFalse();
})->with('dashboardModels');
