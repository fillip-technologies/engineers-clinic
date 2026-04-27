<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $adminRole = Role::where('name', 'admin')->first();
        $collegeRole = Role::where('name', 'college')->first();
        $studentRole = Role::where('name', 'student')->first();

        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $adminRole->id,
        ]);

        // Create College User
        User::create([
            'name' => 'College User',
            'email' => 'college@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $collegeRole->id,
        ]);

        // Create Student User
        User::create([
            'name' => 'Student User',
            'email' => 'student@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $studentRole->id,
        ]);
    }
}
