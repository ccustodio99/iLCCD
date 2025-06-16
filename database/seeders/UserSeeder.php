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
        ];

        foreach ($departments as $dept) {
            User::factory()->create([
                'name' => $dept . ' Head',
                'email' => Str::slug($dept) . '.head@example.com',
                'password' => Hash::make('Password1'),
                'role' => 'head',
                'department' => $dept,
            ]);
        }

        // Additional staff across departments
        foreach ($departments as $dept) {
            User::factory()->count(2)->create([
                'role' => 'staff',
                'department' => $dept,
            ]);
        }
    }
}
