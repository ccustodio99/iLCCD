<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Core demo accounts
        User::factory()->create([
            'name' => 'Demo Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'admin',
            'department' => 'ITRC',
        ]);

        User::factory()->create([
            'name' => 'Demo President',
            'email' => 'president@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'head',
            'department' => 'Administration',
            'designation' => 'President',
        ]);

        $departments = [
            'Nursing',
            'CHTM',
            'CCS',
            'BED Department',
            'Non-Teaching Department',
            'ITRC',
            'Finance Office',
        ];

        foreach ($departments as $dept) {
            // Department head
            User::factory()->create([
                'name' => $dept . ' Head',
                'email' => Str::slug($dept) . '.head@example.com',
                'password' => Hash::make('Password1'),
                'role' => 'head',
                'department' => $dept,
            ]);

            // Single staff member
            User::factory()->create([
                'role' => 'staff',
                'department' => $dept,
                'password' => Hash::make('Password1'),
            ]);
        }
    }
}
