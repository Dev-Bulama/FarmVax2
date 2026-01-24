<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AlertPreference;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        $admin = User::create([
            'name' => 'FarmVax Admin',
            'email' => 'admin@farmvax.com',
            'phone' => '+234 123 456 7890',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'address' => '123 Admin Street',
            'city' => 'Lagos',
            'state' => 'Lagos',
            'country' => 'Nigeria',
            'status' => 'active',
            'is_approved' => true,
            'email_verified_at' => now(),
        ]);

        // Create alert preferences for admin
        AlertPreference::create([
            'user_id' => $admin->id,
            'primary_phone' => $admin->phone,
            'primary_email' => $admin->email,
            'preferred_method' => 'email',
        ]);

        $this->command->info('✓ Default Admin created successfully!');
        $this->command->info('  Email: admin@farmvax.com');
        $this->command->info('  Password: password123');
        $this->command->line('');
    }
}