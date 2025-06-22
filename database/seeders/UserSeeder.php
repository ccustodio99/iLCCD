<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
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

        $deptIds = collect($departments)->mapWithKeys(function ($name) {
            $dept = Department::firstOrCreate(['name' => $name]);

            return [$name => $dept->id];
        });

        // Core demo accounts
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Demo Admin',
                'password' => Hash::make('Password1'),
                'role' => 'admin',
                'department_id' => $deptIds['ITRC'],
                'designation' => 'System Administrator',
                'contact_info' => '09170000000',
                'profile_photo_path' => 'profile_photos/WJgnAHDtvniFszaj0BtsWmU8PyUww2o5hhA4LpK2.png',
            ]
        );

        User::updateOrCreate(
            ['email' => 'president@example.com'],
            [
                'name' => 'Demo President',
                'password' => Hash::make('Password1'),
                'role' => 'head',
                'department_id' => $deptIds['President Department'],
                'designation' => 'President',
                'contact_info' => '09170000001',
                'profile_photo_path' => 'profile_photos/MRIwDpqyCOwJjX7oIeOjI6ZTFwWXNlRVqQ5h0Sk6.jpg',
            ]
        );

        // Dedicated finance staff account for docs
        User::updateOrCreate(
            ['email' => 'finance@example.com'],
            [
                'name' => 'Finance Officer',
                'password' => Hash::make('Password1'),
                'role' => 'staff',
                'department_id' => $deptIds['Finance Office'],
                'designation' => 'Finance Staff',
                'contact_info' => '09170000002',
            ]
        );

        foreach ($departments as $index => $dept) {
            $headEmail = $index === 0 ? 'head@example.com' : Str::slug($dept).'.head@example.com';

            // Department head
            User::updateOrCreate(
                ['email' => $headEmail],
                [
                    'name' => $dept.' Head',
                    'password' => Hash::make('Password1'),
                    'role' => 'head',
                    'department_id' => $deptIds[$dept],
                    'designation' => 'Department Head',
                    'contact_info' => '09170000'.str_pad((string) $index, 2, '0', STR_PAD_LEFT),
                ]
            );

            // Skip creating another staff for finance office or president department
            if ($dept === 'Finance Office' || $dept === 'President Department') {
                continue;
            }

            // Single staff member
            User::updateOrCreate(
                ['email' => Str::slug($dept).'.staff@example.com'],
                [
                    'name' => $dept.' Staff',
                    'password' => Hash::make('Password1'),
                    'role' => 'staff',
                    'department_id' => $deptIds[$dept],
                    'designation' => 'Staff',
                    'contact_info' => '09170001'.str_pad((string) $index, 2, '0', STR_PAD_LEFT),
                ]
            );
        }
    }
}
