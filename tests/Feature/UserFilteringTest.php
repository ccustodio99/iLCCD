<?php

use App\Models\User;

it('filters users by role', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create(['role' => 'staff', 'name' => 'Staff User']);
    User::factory()->create(['role' => 'itrc', 'name' => 'ITRC User']);

    $this->actingAs($admin);
    $response = $this->get('/users?role=staff');
    $response->assertSee('Staff User');
    $response->assertDontSee('ITRC User');
});

it('searches users by name and email', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
    User::factory()->create(['name' => 'Jane', 'email' => 'jane@example.com']);

    $this->actingAs($admin);
    $response = $this->get('/users?search=john');
    $response->assertSee('John Doe');
    $response->assertDontSee('Jane');
});

it('filters users by department', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create(['name' => 'CCS User', 'department' => 'CCS']);
    User::factory()->create(['name' => 'HR User', 'department' => 'HR']);

    $this->actingAs($admin);
    $response = $this->get('/users?department=CCS');
    $response->assertSee('CCS User');
    $response->assertDontSee('HR User');
});

it('filters users by status', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create(['name' => 'Active', 'is_active' => true]);
    User::factory()->create(['name' => 'Inactive', 'is_active' => false]);

    $this->actingAs($admin);
    $response = $this->get('/users?status=inactive');
    $response->assertSee('Inactive');
    $response->assertDontSee('Active');
});
