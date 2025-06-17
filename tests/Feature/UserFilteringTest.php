<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\UserSeeder;

it('filters users by role', function () {
    $this->seed(UserSeeder::class);
    $admin = User::firstWhere('role', 'admin');
    User::create([
        'name' => 'Staff User',
        'email' => 'staff.user@example.com',
        'password' => Hash::make('Password1'),
        'role' => 'staff',
        'department' => 'ITRC',
    ]);
    User::create([
        'name' => 'ITRC User',
        'email' => 'itrc.user@example.com',
        'password' => Hash::make('Password1'),
        'role' => 'itrc',
        'department' => 'ITRC',
    ]);

    $this->actingAs($admin);
    $response = $this->get('/users?role=staff');
    $response->assertSee('Staff User');
    $response->assertDontSee('ITRC User');
});

it('searches users by name and email', function () {
    $this->seed(UserSeeder::class);
    $admin = User::firstWhere('role', 'admin');
    User::create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => Hash::make('Password1'),
        'role' => 'staff',
        'department' => 'CCS',
    ]);
    User::create([
        'name' => 'Jane',
        'email' => 'jane@example.com',
        'password' => Hash::make('Password1'),
        'role' => 'staff',
        'department' => 'CCS',
    ]);

    $this->actingAs($admin);
    $response = $this->get('/users?search=john');
    $response->assertSee('John Doe');
    $response->assertDontSee('Jane');
});

it('filters users by department', function () {
    $this->seed(UserSeeder::class);
    $admin = User::firstWhere('role', 'admin');
    User::create([
        'name' => 'CCS User',
        'email' => 'ccs.user@example.com',
        'password' => Hash::make('Password1'),
        'role' => 'staff',
        'department' => 'CCS',
    ]);
    User::create([
        'name' => 'HR User',
        'email' => 'hr.user@example.com',
        'password' => Hash::make('Password1'),
        'role' => 'staff',
        'department' => 'HR',
    ]);

    $this->actingAs($admin);
    $response = $this->get('/users?department=CCS');
    $response->assertSee('CCS User');
    $response->assertDontSee('HR User');
});

it('filters users by status', function () {
    $this->seed(UserSeeder::class);
    $admin = User::firstWhere('role', 'admin');
    User::create([
        'name' => 'Active',
        'email' => 'active@example.com',
        'password' => Hash::make('Password1'),
        'role' => 'staff',
        'department' => 'CCS',
        'is_active' => true,
    ]);
    User::create([
        'name' => 'Inactive',
        'email' => 'inactive@example.com',
        'password' => Hash::make('Password1'),
        'role' => 'staff',
        'department' => 'CCS',
        'is_active' => false,
    ]);

    $this->actingAs($admin);
    $response = $this->get('/users?status=inactive');
    $response->assertSee('Inactive');
    $response->assertDontSee('Active');
});
