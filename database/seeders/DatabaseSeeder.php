<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create the System Admin
        User::factory()->create([
            'name' => 'System Admin',
            'email' => 'admin@system.com',
            'password' => Hash::make('password123'),
            'role' => UserRole::ADMIN->value,
        ]);

        // 2. Create an Event Organizer
        User::factory()->create([
            'name' => 'Tech Workshop Organizer',
            'email' => 'organizer@tech.com',
            'password' => Hash::make('password123'),
            'role' => UserRole::ORGANIZER->value,
        ]);

        // 3. Create a standard Attendee
        User::factory()->create([
            'name' => 'Regular Attendee',
            'email' => 'attendee@mail.com',
            'password' => Hash::make('password123'),
            'role' => UserRole::ATTENDEE->value,
        ]);
    }
}