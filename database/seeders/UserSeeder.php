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

        // Dedicated finance staff account for docs
        User::factory()->create([
            'name' => 'Finance Officer',
            'email' => 'finance@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'staff',
            'department' => 'Finance Office',
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

        foreach ($departments as $index => $dept) {
            // Department head
            User::factory()->create([
                'name' => $dept . ' Head',
                'email' => $index === 0 ? 'head@example.com' : Str::slug($dept) . '.head@example.com',
                'password' => Hash::make('Password1'),
                'role' => 'head',
                'department' => $dept,
            ]);

            // Skip creating another staff for finance office
            if ($dept === 'Finance Office') {
                continue;
            }

            // Single staff member
            User::factory()->create([
                'name' => $dept . ' Staff',
                'email' => Str::slug($dept) . '.staff@example.com',
                'password' => Hash::make('Password1'),
                'role' => 'staff',
                'department' => $dept,
            ]);
        }
    }
}
