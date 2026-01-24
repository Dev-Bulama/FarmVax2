<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\AnimalHealthProfessional;
use App\Models\Volunteer;
use App\Models\State;
use App\Models\Lga;
use App\Models\ProfessionalType;
use App\Models\Specialization;
use App\Models\ServiceArea;
use App\Models\VolunteerStat;

class DummyUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Get some locations
        $kanoState = State::where('name', 'Kano')->first();
        $lagosState = State::where('name', 'Lagos')->first();
        $abujaState = State::where('name', 'Abuja (FCT)')->first();

        $kanoLga = $kanoState ? Lga::where('state_id', $kanoState->id)->first() : null;
        $lagosLga = $lagosState ? Lga::where('state_id', $lagosState->id)->first() : null;
        $abujaLga = $abujaState ? Lga::where('state_id', $abujaState->id)->first() : null;

        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@farmvax.com',
            'phone' => '+2348012345678',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'address' => '123 Admin Street, Abuja',
            'state_id' => $abujaState?->id,
            'lga_id' => $abujaLga?->id,
            'latitude' => 9.0765,
            'longitude' => 7.3986,
            'account_status' => 'active',
            'status' => 'active'
        ]);

        // Create Farmers
        $farmers = [
            [
                'name' => 'Musa Ibrahim',
                'email' => 'farmer1@farmvax.com',
                'phone' => '+2348011111111',
                'password' => Hash::make('farmer123'),
                'role' => 'farmer',
                'address' => '45 Farm Road, Kano',
                'state_id' => $kanoState?->id,
                'lga_id' => $kanoLga?->id,
                'latitude' => 12.0022,
                'longitude' => 8.5920,
                'account_status' => 'active',
                'status' => 'active'
            ],
            [
                'name' => 'Fatima Abubakar',
                'email' => 'farmer2@farmvax.com',
                'phone' => '+2348022222222',
                'password' => Hash::make('farmer123'),
                'role' => 'farmer',
                'address' => '78 Village Road, Lagos',
                'state_id' => $lagosState?->id,
                'lga_id' => $lagosLga?->id,
                'latitude' => 6.5244,
                'longitude' => 3.3792,
                'account_status' => 'active',
                'status' => 'active'
            ],
            [
                'name' => 'Audu Mohammed',
                'email' => 'farmer3@farmvax.com',
                'phone' => '+2348033333333',
                'password' => Hash::make('farmer123'),
                'role' => 'farmer',
                'address' => '12 Pastoral Lane, Kano',
                'state_id' => $kanoState?->id,
                'lga_id' => $kanoLga?->id,
                'latitude' => 11.9854,
                'longitude' => 8.5164,
                'account_status' => 'active',
                'status' => 'active'
            ]
        ];

        foreach ($farmers as $farmerData) {
            User::create($farmerData);
        }

        // Create Professionals
        $professionalType = ProfessionalType::first();
        $specialization = Specialization::first();
        $serviceArea = ServiceArea::first();

        $professionalsData = [
            [
                'user' => [
                    'name' => 'Dr. Ahmed Suleiman',
                    'email' => 'professional1@farmvax.com',
                    'phone' => '+2348044444444',
                    'password' => Hash::make('professional123'),
                    'role' => 'animal_health_professional',
                    'address' => '56 Medical Center, Kano',
                    'state_id' => $kanoState?->id,
                    'lga_id' => $kanoLga?->id,
                    'latitude' => 12.0000,
                    'longitude' => 8.5200,
                    'account_status' => 'active',
                    'status' => 'active'
                ],
                'professional' => [
                    'license_number' => 'VET/KN/001/2020',
                    'years_experience' => 8,
                    'verification_status' => 'approved'
                ]
            ],
            [
                'user' => [
                    'name' => 'Dr. Ngozi Okafor',
                    'email' => 'professional2@farmvax.com',
                    'phone' => '+2348055555555',
                    'password' => Hash::make('professional123'),
                    'role' => 'animal_health_professional',
                    'address' => '90 Clinic Road, Lagos',
                    'state_id' => $lagosState?->id,
                    'lga_id' => $lagosLga?->id,
                    'latitude' => 6.5000,
                    'longitude' => 3.3500,
                    'account_status' => 'active',
                    'status' => 'active'
                ],
                'professional' => [
                    'license_number' => 'VET/LA/002/2019',
                    'years_experience' => 12,
                    'verification_status' => 'approved'
                ]
            ],
            [
                'user' => [
                    'name' => 'Dr. Yusuf Garba',
                    'email' => 'professional3@farmvax.com',
                    'phone' => '+2348066666666',
                    'password' => Hash::make('professional123'),
                    'role' => 'animal_health_professional',
                    'address' => '23 Health Center, Abuja',
                    'state_id' => $abujaState?->id,
                    'lga_id' => $abujaLga?->id,
                    'latitude' => 9.0500,
                    'longitude' => 7.3800,
                    'account_status' => 'active',
                    'status' => 'active'
                ],
                'professional' => [
                    'license_number' => 'VET/AB/003/2021',
                    'years_experience' => 5,
                    'verification_status' => 'pending'
                ]
            ]
        ];

        foreach ($professionalsData as $profData) {
            $user = User::create($profData['user']);

            // Create professional profile
            AnimalHealthProfessional::create([
                'user_id' => $user->id,
                'professional_type' => 'veterinarian',
                'license_number' => $profData['professional']['license_number'],
                'specialization' => 'General Practice',
                'experience_years' => $profData['professional']['years_experience'],
                'approval_status' => $profData['professional']['verification_status'],
            ]);
        }

        // Create Volunteers
        $volunteersData = [
            [
                'name' => 'Aisha Bello',
                'email' => 'volunteer1@farmvax.com',
                'phone' => '+2348077777777',
                'password' => Hash::make('volunteer123'),
                'role' => 'volunteer',
                'address' => '34 Community Center, Kano',
                'state_id' => $kanoState?->id,
                'lga_id' => $kanoLga?->id,
                'latitude' => 12.0100,
                'longitude' => 8.5300,
                'account_status' => 'active',
                'status' => 'active'
            ],
            [
                'name' => 'Chinedu Obi',
                'email' => 'volunteer2@farmvax.com',
                'phone' => '+2348088888888',
                'password' => Hash::make('volunteer123'),
                'role' => 'volunteer',
                'address' => '67 Extension Office, Lagos',
                'state_id' => $lagosState?->id,
                'lga_id' => $lagosLga?->id,
                'latitude' => 6.5100,
                'longitude' => 3.3600,
                'account_status' => 'active',
                'status' => 'active'
            ]
        ];

        foreach ($volunteersData as $volData) {
            $user = User::create($volData);

            // Create volunteer profile
            $volunteer = Volunteer::create([
                'user_id' => $user->id,
                'organization' => 'FarmVax Community Outreach',
                'motivation' => 'Helping farmers access better veterinary services',
                'availability' => 'Weekends and evenings',
                'status' => 'active'
            ]);

            // Create volunteer stats
            VolunteerStat::create([
                'volunteer_id' => $volunteer->id,
                'total_enrollments' => 0,
                'active_farmers' => 0,
                'total_points' => 0,
                'current_badge' => 'bronze',
                'rank' => 0
            ]);
        }
    }
}
