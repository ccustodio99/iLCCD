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
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Demo Admin',
                'password' => Hash::make('Password1'),
                'role' => 'admin',
                'department' => 'ITRC',
            ]
        );

        User::updateOrCreate(
            ['email' => 'president@example.com'],
            [
                'name' => 'Demo President',
                'password' => Hash::make('Password1'),
                'role' => 'head',
                'department' => 'President Department',
                'designation' => 'President',
            ]
        );

        // Dedicated finance staff account for docs
        User::updateOrCreate(
            ['email' => 'finance@example.com'],
            [
                'name' => 'Finance Officer',
                'password' => Hash::make('Password1'),
                'role' => 'staff',
                'department' => 'Finance Office',
            ]
        );

        $departments = [
            'Nursing',
            'CHTM',
            'CCS',
            'BED Department',
            'Non-Teaching Department',
            'ITRC',
            'Finance Office',
            'President Department',
        ];


        foreach ($departments as $index => $dept) {

            // Department head
            User::updateOrCreate(
                ['email' => $index === 0 ? 'head@example.com' : Str::slug($dept) . '.head@example.com'],
                [
                    'name' => $dept . ' Head',
                    'password' => Hash::make('Password1'),
                    'role' => 'head',
                    'department' => $dept,
                ]
            );


            // Skip creating another staff for finance office or president department
            if ($dept === 'Finance Office' || $dept === 'President Department') {
                continue;
            }

            // Single staff member
            User::updateOrCreate(
                ['email' => Str::slug($dept) . '.staff@example.com'],
                [
                    'name' => $dept . ' Staff',
                    'password' => Hash::make('Password1'),
                    'role' => 'staff',
                    'department' => $dept,
                ]
            );
        }
    }
}
