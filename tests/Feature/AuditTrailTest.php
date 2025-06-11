<?php

use App\Models\AuditTrail;
use App\Models\User;

it('records audit trail on ticket creation', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->post('/tickets', [
        'category' => 'IT',
        'subject' => 'Printer',
        'description' => 'Broken',
    ])->assertRedirect('/tickets');

    expect(AuditTrail::where('auditable_type', App\Models\Ticket::class)->where('action', 'created')->exists())->toBeTrue();
});
