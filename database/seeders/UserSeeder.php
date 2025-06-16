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
            'role' => 'president',
            'department' => 'Administration',
        ]);


        User::factory()->create([
            'name' => 'Demo Finance',
            'email' => 'finance@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'finance',
            'department' => 'Finance Office',
        ]);

        User::factory()->create([
            'name' => 'Demo Registrar',
            'email' => 'registrar@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'registrar',
            'department' => 'Registrar',
        ]);

        User::factory()->create([
            'name' => 'Demo HR',
            'email' => 'hr@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'hr',
            'department' => 'HR Department',
        ]);

        User::factory()->create([
            'name' => 'Demo Clinic',
            'email' => 'clinic@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'clinic',
            'department' => 'Clinic',
        ]);

        User::factory()->create([
            'name' => 'Demo ITRC',
            'email' => 'itrc@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'itrc',
            'department' => 'ITRC',
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

        // Generic demo accounts used in docs
        User::factory()->create([
            'name' => 'Demo Staff',
            'email' => 'staff@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'staff',
            'department' => 'CCS',
        ]);

        User::factory()->create([
            'name' => 'Demo User',
            'email' => 'user@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'user',
            'department' => 'CCS',
        ]);

        User::factory()->create([
            'name' => 'Demo Faculty',
            'email' => 'faculty@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'staff',
            'department' => 'Faculty/Staff',
        ]);

        User::factory()->create([
            'name' => 'Demo Academic',
            'email' => 'academic@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'academic',
            'department' => 'Academic Units',
        ]);
    }
}
