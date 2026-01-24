<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\DataCollectorProfile;
use App\Models\AlertPreference;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');

        // 1. Create Admin User
        $this->command->info('👤 Creating admin user...');
        $admin = User::create([
            'name' => 'FarmVax Admin',
            'email' => 'admin@farmvax.com',
            'phone' => '+234 801 234 5678',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'is_approved' => true,
            'country' => 'Nigeria',
            'state' => 'Lagos',
            'city' => 'Ikeja',
            'address' => '123 Admin Street, Ikeja',
            'email_verified_at' => now(),
        ]);

        AlertPreference::create(['user_id' => $admin->id]);

        $this->command->info('✅ Admin created: admin@farmvax.com / password');

        // 2. Create Approved Data Collectors
        $this->command->info('👥 Creating approved data collectors...');
        
        $approvedCollectors = [
            [
                'name' => 'Dr. Adebayo Johnson',
                'email' => 'adebayo@farmvax.com',
                'phone' => '+234 802 345 6789',
                'organization' => 'Lagos State Veterinary Services',
                'position' => 'Senior Veterinarian',
                'territory' => 'Lagos Mainland',
                'education' => 'DVM',
            ],
            [
                'name' => 'Mrs. Amina Bello',
                'email' => 'amina@farmvax.com',
                'phone' => '+234 803 456 7890',
                'organization' => 'Federal Ministry of Agriculture',
                'position' => 'Field Officer',
                'territory' => 'Kano Central',
                'education' => 'BSc Agriculture',
            ],
            [
                'name' => 'Mr. Chukwudi Okafor',
                'email' => 'chukwudi@farmvax.com',
                'phone' => '+234 804 567 8901',
                'organization' => 'Enugu Agricultural Extension',
                'position' => 'Extension Officer',
                'territory' => 'Enugu North',
                'education' => 'BSc Animal Science',
            ],
        ];

        foreach ($approvedCollectors as $collectorData) {
            $user = User::create([
                'name' => $collectorData['name'],
                'email' => $collectorData['email'],
                'phone' => $collectorData['phone'],
                'password' => Hash::make('password'),
                'role' => 'data_collector',
                'status' => 'active',
                'is_approved' => true,
                'approved_at' => now()->subDays(rand(10, 30)),
                'approved_by' => $admin->id,
                'country' => 'Nigeria',
                'state' => explode(' ', $collectorData['territory'])[0],
                'city' => $collectorData['territory'],
                'address' => rand(1, 999) . ' ' . Str::random(10) . ' Street',
                'email_verified_at' => now(),
            ]);

            DataCollectorProfile::create([
                'user_id' => $user->id,
                'organization' => $collectorData['organization'],
                'position' => $collectorData['position'],
                'education_level' => $collectorData['education'],
                'experience' => rand(2, 10) . ' years of experience in livestock management',
                'reason_for_applying' => 'I want to help improve livestock health and support farmers in my community.',
                'id_card_type' => 'National ID',
                'id_card_number' => 'NIN' . rand(10000000, 99999999),
                'assigned_territory' => $collectorData['territory'],
                'coverage_area' => $collectorData['territory'] . ' and surrounding areas',
                'approval_status' => 'approved',
                'submitted_at' => now()->subDays(rand(10, 30)),
                'reviewed_at' => now()->subDays(rand(1, 9)),
                'reviewed_by' => $admin->id,
                'total_submissions' => rand(5, 25),
                'approved_submissions' => rand(3, 20),
                'accuracy_rate' => rand(75, 98),
            ]);

            AlertPreference::create(['user_id' => $user->id]);
            
            $this->command->info("✅ Approved Data Collector: {$collectorData['email']} / password");
        }

        // 3. Create Pending Data Collectors
        $this->command->info('⏳ Creating pending data collectors...');
        
        $pendingCollectors = [
            [
                'name' => 'Mr. Ibrahim Yusuf',
                'email' => 'ibrahim@example.com',
                'phone' => '+234 805 678 9012',
                'organization' => 'Kaduna Livestock Association',
                'position' => 'Assistant Veterinarian',
                'territory' => 'Kaduna South',
            ],
            [
                'name' => 'Miss Blessing Eze',
                'email' => 'blessing@example.com',
                'phone' => '+234 806 789 0123',
                'organization' => 'Rivers State Farm Initiative',
                'position' => 'Livestock Coordinator',
                'territory' => 'Port Harcourt',
            ],
        ];

        foreach ($pendingCollectors as $collectorData) {
            $user = User::create([
                'name' => $collectorData['name'],
                'email' => $collectorData['email'],
                'phone' => $collectorData['phone'],
                'password' => Hash::make('password'),
                'role' => 'data_collector',
                'status' => 'pending',
                'is_approved' => false,
                'country' => 'Nigeria',
                'state' => explode(' ', $collectorData['territory'])[0],
                'city' => $collectorData['territory'],
                'address' => rand(1, 999) . ' ' . Str::random(10) . ' Street',
                'email_verified_at' => now(),
            ]);

            DataCollectorProfile::create([
                'user_id' => $user->id,
                'organization' => $collectorData['organization'],
                'position' => $collectorData['position'],
                'education_level' => 'BSc Agriculture',
                'experience' => rand(1, 5) . ' years of experience',
                'reason_for_applying' => 'I am passionate about livestock health and want to contribute to disease prevention in my community.',
                'id_card_type' => 'National ID',
                'id_card_number' => 'NIN' . rand(10000000, 99999999),
                'assigned_territory' => $collectorData['territory'],
                'approval_status' => 'pending',
                'submitted_at' => now()->subDays(rand(1, 7)),
            ]);

            AlertPreference::create(['user_id' => $user->id]);
            
            $this->command->info("✅ Pending Data Collector: {$collectorData['email']} / password");
        }

        // 4. Create Individual Farmers
        $this->command->info('👨‍🌾 Creating individual farmers...');
        
        $farmers = [
            ['name' => 'Alhaji Musa Tanko', 'email' => 'musa@example.com', 'phone' => '+234 807 890 1234', 'state' => 'Kano', 'city' => 'Kano City'],
            ['name' => 'Mrs. Grace Okonkwo', 'email' => 'grace@example.com', 'phone' => '+234 808 901 2345', 'state' => 'Anambra', 'city' => 'Onitsha'],
            ['name' => 'Mr. Tunde Adeyemi', 'email' => 'tunde@example.com', 'phone' => '+234 809 012 3456', 'state' => 'Oyo', 'city' => 'Ibadan'],
            ['name' => 'Mrs. Fatima Abubakar', 'email' => 'fatima@example.com', 'phone' => '+234 810 123 4567', 'state' => 'Katsina', 'city' => 'Katsina'],
            ['name' => 'Mr. Emeka Nwosu', 'email' => 'emeka@example.com', 'phone' => '+234 811 234 5678', 'state' => 'Imo', 'city' => 'Owerri'],
            ['name' => 'Chief Olayinka Ademola', 'email' => 'olayinka@example.com', 'phone' => '+234 812 345 6789', 'state' => 'Osun', 'city' => 'Osogbo'],
            ['name' => 'Mallam Sani Garba', 'email' => 'sani@example.com', 'phone' => '+234 813 456 7890', 'state' => 'Sokoto', 'city' => 'Sokoto'],
            ['name' => 'Mrs. Ngozi Okoli', 'email' => 'ngozi@example.com', 'phone' => '+234 814 567 8901', 'state' => 'Ebonyi', 'city' => 'Abakaliki'],
            ['name' => 'Mr. Yahaya Danjuma', 'email' => 'yahaya@example.com', 'phone' => '+234 815 678 9012', 'state' => 'Taraba', 'city' => 'Jalingo'],
            ['name' => 'Chief (Mrs.) Abosede Williams', 'email' => 'abosede@example.com', 'phone' => '+234 816 789 0123', 'state' => 'Lagos', 'city' => 'Lagos Island'],
        ];

        foreach ($farmers as $farmerData) {
            $user = User::create([
                'name' => $farmerData['name'],
                'email' => $farmerData['email'],
                'phone' => $farmerData['phone'],
                'password' => Hash::make('password'),
                'role' => 'individual',
                'status' => 'active',
                'is_approved' => true,
                'country' => 'Nigeria',
                'state' => $farmerData['state'],
                'city' => $farmerData['city'],
                'address' => rand(1, 999) . ' Farm Road, ' . $farmerData['city'],
                'email_verified_at' => now(),
            ]);

            AlertPreference::create([
                'user_id' => $user->id,
                'sms_enabled' => true,
                'outbreak_alerts' => true,
                'vaccine_alerts' => true,
                'preferred_language' => 'english',
            ]);
            
            $this->command->info("✅ Individual Farmer: {$farmerData['email']} / password");
        }

        // Summary
        $this->command->info('');
        $this->command->line('==========================================');
        $this->command->info('🎉 Database seeding completed!');
        $this->command->line('==========================================');
        $this->command->info('');
        $this->command->info('📋 LOGIN CREDENTIALS (All passwords: password)');
        $this->command->line('------------------------------------------');
        $this->command->info('👑 ADMIN:');
        $this->command->line('   Email: admin@farmvax.com');
        $this->command->line('   Password: password');
        $this->command->info('');
        $this->command->info('👥 APPROVED DATA COLLECTORS:');
        $this->command->line('   adebayo@farmvax.com / password');
        $this->command->line('   amina@farmvax.com / password');
        $this->command->line('   chukwudi@farmvax.com / password');
        $this->command->info('');
        $this->command->info('⏳ PENDING DATA COLLECTORS:');
        $this->command->line('   ibrahim@example.com / password');
        $this->command->line('   blessing@example.com / password');
        $this->command->info('');
        $this->command->info('👨‍🌾 INDIVIDUAL FARMERS (10 users):');
        $this->command->line('   musa@example.com / password');
        $this->command->line('   grace@example.com / password');
        $this->command->line('   tunde@example.com / password');
        $this->command->line('   ... and 7 more farmers');
        $this->command->info('');
        $this->command->line('==========================================');
    }
}