<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('tickets:check-sla', function () {
    $overdue = \App\Models\Ticket::where('status', 'open')
        ->whereNotNull('due_at')
        ->where('due_at', '<', now())
        ->get();

    foreach ($overdue as $ticket) {
        $ticket->update([
            'status' => 'escalated',
            'escalated_at' => now(),
        ]);
        \Illuminate\Support\Facades\Log::info('Ticket escalated', ['ticket_id' => $ticket->id]);
    }
})->purpose('Mark overdue tickets as escalated');

Artisan::command('ensure:storage-link', function () {
    if (! file_exists(public_path('storage'))) {
        $this->info('Creating storage symlink...');
        $this->call('storage:link');
    }
})->purpose('Ensure the public/storage link exists');

Artisan::command('settings:clear-cache', function () {
    $keys = \App\Models\Setting::pluck('key');

    foreach ($keys as $key) {
        \Illuminate\Support\Facades\Cache::forget("setting:{$key}");
    }

    $this->info('Settings cache cleared');
})->purpose('Clear cached values for all settings');
