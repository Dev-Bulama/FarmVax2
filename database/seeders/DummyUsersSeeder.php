<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\AnimalHealthProfessional;
use App\Models\Volunteer;
use App\Models\State;
use App\Models\Lga;
use App\Models\VolunteerStat;

class DummyUsersSeeder extends Seeder
{
    public function run(): void
    {
        $kanoState  = State::where('name', 'Kano')->first();
        $lagosState = State::where('name', 'Lagos')->first();
        $abujaState = State::where('name', 'Abuja (FCT)')->first();

        $kanoLga  = $kanoState  ? Lga::where('state_id', $kanoState->id)->first()  : null;
        $lagosLga = $lagosState ? Lga::where('state_id', $lagosState->id)->first() : null;
        $abujaLga = $abujaState ? Lga::where('state_id', $abujaState->id)->first() : null;

        // Admin — use firstOrCreate so re-running the seeder never fails on unique email
        $adminData = [
            'name'           => 'Admin User',
            'phone'          => '+2348012345678',
            'password'       => Hash::make('admin123'),
            'role'           => 'admin',
            'address'        => '123 Admin Street, Abuja',
            'state_id'       => $abujaState?->id,
            'lga_id'         => $abujaLga?->id,
            'account_status' => 'active',
            'status'         => 'active',
        ];
        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'latitude')) {
            $adminData['latitude']  = 9.0765;
            $adminData['longitude'] = 7.3986;
        }
        User::firstOrCreate(['email' => 'admin@farmvax.com'], $adminData);

        // Farmers
        $farmersData = [
            [
                'name'    => 'Musa Ibrahim',
                'email'   => 'farmer1@farmvax.com',
                'phone'   => '+2348011111111',
                'role'    => 'farmer',
                'address' => '45 Farm Road, Kano',
                'state_id' => $kanoState?->id,
                'lga_id'  => $kanoLga?->id,
                'lat'     => 12.0022,
                'lng'     => 8.5920,
            ],
            [
                'name'    => 'Fatima Abubakar',
                'email'   => 'farmer2@farmvax.com',
                'phone'   => '+2348022222222',
                'role'    => 'farmer',
                'address' => '78 Village Road, Lagos',
                'state_id' => $lagosState?->id,
                'lga_id'  => $lagosLga?->id,
                'lat'     => 6.5244,
                'lng'     => 3.3792,
            ],
            [
                'name'    => 'Audu Mohammed',
                'email'   => 'farmer3@farmvax.com',
                'phone'   => '+2348033333333',
                'role'    => 'farmer',
                'address' => '12 Pastoral Lane, Kano',
                'state_id' => $kanoState?->id,
                'lga_id'  => $kanoLga?->id,
                'lat'     => 11.9854,
                'lng'     => 8.5164,
            ],
        ];

        $hasLatLon = \Illuminate\Support\Facades\Schema::hasColumn('users', 'latitude');

        foreach ($farmersData as $fd) {
            $base = [
                'password'       => Hash::make('farmer123'),
                'role'           => $fd['role'],
                'address'        => $fd['address'],
                'state_id'       => $fd['state_id'],
                'lga_id'         => $fd['lga_id'],
                'account_status' => 'active',
                'status'         => 'active',
            ];
            if ($hasLatLon) {
                $base['latitude']  = $fd['lat'];
                $base['longitude'] = $fd['lng'];
            }
            User::firstOrCreate(
                ['email' => $fd['email']],
                array_merge(['name' => $fd['name'], 'phone' => $fd['phone']], $base)
            );
        }

        // Professionals
        $professionalsData = [
            [
                'name'       => 'Dr. Ahmed Suleiman',
                'email'      => 'professional1@farmvax.com',
                'phone'      => '+2348044444444',
                'address'    => '56 Medical Center, Kano',
                'state_id'   => $kanoState?->id,
                'lga_id'     => $kanoLga?->id,
                'lat'        => 12.0000,
                'lng'        => 8.5200,
                'license'    => 'VET/KN/001/2020',
                'experience' => 8,
                'approval'   => 'approved',
            ],
            [
                'name'       => 'Dr. Ngozi Okafor',
                'email'      => 'professional2@farmvax.com',
                'phone'      => '+2348055555555',
                'address'    => '90 Clinic Road, Lagos',
                'state_id'   => $lagosState?->id,
                'lga_id'     => $lagosLga?->id,
                'lat'        => 6.5000,
                'lng'        => 3.3500,
                'license'    => 'VET/LA/002/2019',
                'experience' => 12,
                'approval'   => 'approved',
            ],
            [
                'name'       => 'Dr. Yusuf Garba',
                'email'      => 'professional3@farmvax.com',
                'phone'      => '+2348066666666',
                'address'    => '23 Health Center, Abuja',
                'state_id'   => $abujaState?->id,
                'lga_id'     => $abujaLga?->id,
                'lat'        => 9.0500,
                'lng'        => 7.3800,
                'license'    => 'VET/AB/003/2021',
                'experience' => 5,
                'approval'   => 'pending',
            ],
        ];

        foreach ($professionalsData as $pd) {
            $base = [
                'password'       => Hash::make('professional123'),
                'role'           => 'animal_health_professional',
                'address'        => $pd['address'],
                'state_id'       => $pd['state_id'],
                'lga_id'         => $pd['lga_id'],
                'account_status' => 'active',
                'status'         => 'active',
            ];
            if ($hasLatLon) {
                $base['latitude']  = $pd['lat'];
                $base['longitude'] = $pd['lng'];
            }
            $user = User::firstOrCreate(
                ['email' => $pd['email']],
                array_merge(['name' => $pd['name'], 'phone' => $pd['phone']], $base)
            );

            AnimalHealthProfessional::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'professional_type' => 'veterinarian',
                    'license_number'    => $pd['license'],
                    'specialization'    => 'General Practice',
                    'experience_years'  => $pd['experience'],
                    'approval_status'   => $pd['approval'],
                ]
            );
        }

        // Volunteers
        $volunteersData = [
            [
                'name'     => 'Aisha Bello',
                'email'    => 'volunteer1@farmvax.com',
                'phone'    => '+2348077777777',
                'address'  => '34 Community Center, Kano',
                'state_id' => $kanoState?->id,
                'lga_id'   => $kanoLga?->id,
                'lat'      => 12.0100,
                'lng'      => 8.5300,
            ],
            [
                'name'     => 'Chinedu Obi',
                'email'    => 'volunteer2@farmvax.com',
                'phone'    => '+2348088888888',
                'address'  => '67 Extension Office, Lagos',
                'state_id' => $lagosState?->id,
                'lga_id'   => $lagosLga?->id,
                'lat'      => 6.5100,
                'lng'      => 3.3600,
            ],
        ];

        foreach ($volunteersData as $vd) {
            $base = [
                'password'       => Hash::make('volunteer123'),
                'role'           => 'volunteer',
                'address'        => $vd['address'],
                'state_id'       => $vd['state_id'],
                'lga_id'         => $vd['lga_id'],
                'account_status' => 'active',
                'status'         => 'active',
            ];
            if ($hasLatLon) {
                $base['latitude']  = $vd['lat'];
                $base['longitude'] = $vd['lng'];
            }
            $user = User::firstOrCreate(
                ['email' => $vd['email']],
                array_merge(['name' => $vd['name'], 'phone' => $vd['phone']], $base)
            );

            $volunteer = Volunteer::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'organization' => 'FarmVax Community Outreach',
                    'motivation'   => 'Helping farmers access better veterinary services',
                    'status'       => 'active',
                ]
            );

            VolunteerStat::firstOrCreate(
                ['volunteer_id' => $volunteer->id],
                [
                    'total_enrollments' => 0,
                    'active_farmers'    => 0,
                    'total_points'      => 0,
                    'current_badge'     => 'bronze',
                    'rank'              => 0,
                ]
            );
        }
    }
}
