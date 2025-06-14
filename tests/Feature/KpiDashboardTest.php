<?php

use App\Models\User;
use App\Models\Ticket;
use App\Models\JobOrder;
use App\Models\Requisition;
use App\Models\AuditTrail;

it('allows authenticated users to view the KPI dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/kpi-dashboard');
    $response->assertStatus(200);
    $response->assertSee('System KPI & Audit Dashboard', false);
});

it('displays metrics and audit logs', function () {
    $user = User::factory()->create();
    Ticket::factory()->create();
    JobOrder::factory()->create();
    Requisition::factory()->create();
    AuditTrail::factory()->create();

    $this->actingAs($user);

    $response = $this->get('/kpi-dashboard');
    $response->assertStatus(200);
    $response->assertSee('Tickets');
    $response->assertSee('Job Orders');
    $response->assertSee('Requisitions');
    $response->assertSee('created');
});

it('prevents non-admin users from exporting audit logs', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->get('/kpi-dashboard/export')->assertForbidden();
});

it('filters audit logs by query parameters', function () {
    $admin = User::factory()->create(['role' => 'admin', 'department' => 'IT']);
    $other = User::factory()->create(['department' => 'HR']);

    $match = AuditTrail::factory()->for($admin)->create([
        'auditable_type' => 'App\\Models\\Ticket',
        'action' => 'created',
        'created_at' => '2024-01-01 12:00:00',
    ]);
    AuditTrail::factory()->for($other)->create([
        'auditable_type' => 'App\\Models\\JobOrder',
        'action' => 'updated',
        'created_at' => '2024-02-01 12:00:00',
    ]);

    $this->actingAs($admin);

    $response = $this->get('/kpi-dashboard?user_id=' . $admin->id . '&department=IT&module=App\\Models\\Ticket&action=created&date_from=2024-01-01&date_to=2024-01-31');

    expect($response->viewData('logs')->total())->toBe(1);
    expect($response->viewData('logs')->items()[0]->id)->toBe($match->id);
});

it('exports filtered audit logs', function () {
    if (!interface_exists('Maatwebsite\\Excel\\Concerns\\FromCollection')) {
        $this->markTestSkipped('Excel package not installed');
    }
    $admin = User::factory()->create(['role' => 'admin']);
    $match = AuditTrail::factory()->for($admin)->create(['action' => 'created']);
    AuditTrail::factory()->create(['action' => 'deleted']);

    $this->actingAs($admin);
    app()->instance('excel', new class {
        public $export;
        public $name;
        public function download($export, $name)
        {
            $this->export = $export;
            $this->name = $name;
            return response('exported');
        }
    });

    $controller = new App\Http\Controllers\KpiAuditDashboardController();
    $request = Illuminate\Http\Request::create('/kpi-dashboard/export', 'GET', ['action' => 'created']);
    $request->setUserResolver(fn () => $admin);
    $response = $controller->export($request);

    $excel = app('excel');
    expect($excel->name)->toBe('kpi_audit_logs.xlsx');
    expect($excel->export->collection()->count())->toBe(1);
    expect($excel->export->collection()->first()['id'])->toBe($match->id);
});
