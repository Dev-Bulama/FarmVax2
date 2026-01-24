<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProfessionalType;
use App\Models\Specialization;
use App\Models\ServiceArea;

class ProfessionalDataSeeder extends Seeder
{
    public function run(): void
    {
        // Professional Types
        $professionalTypes = [
            ['name' => 'Veterinarian', 'description' => 'Licensed veterinary doctor', 'is_active' => true],
            ['name' => 'Veterinary Technician', 'description' => 'Certified veterinary technician', 'is_active' => true],
            ['name' => 'Animal Health Officer', 'description' => 'Government animal health officer', 'is_active' => true],
            ['name' => 'Livestock Extension Officer', 'description' => 'Agricultural extension worker', 'is_active' => true],
            ['name' => 'Animal Nutritionist', 'description' => 'Specialist in animal nutrition', 'is_active' => true],
            ['name' => 'Veterinary Surgeon', 'description' => 'Specialist in animal surgery', 'is_active' => true],
        ];

        foreach ($professionalTypes as $type) {
            ProfessionalType::create($type);
        }

        // Specializations
        $specializations = [
            ['name' => 'Cattle Medicine', 'description' => 'Specialization in cattle health', 'is_active' => true],
            ['name' => 'Small Ruminants', 'description' => 'Sheep and goats specialist', 'is_active' => true],
            ['name' => 'Poultry Medicine', 'description' => 'Poultry health specialist', 'is_active' => true],
            ['name' => 'Large Animal Surgery', 'description' => 'Surgery for large animals', 'is_active' => true],
            ['name' => 'Reproductive Health', 'description' => 'Animal reproduction specialist', 'is_active' => true],
            ['name' => 'Infectious Diseases', 'description' => 'Specialist in animal infectious diseases', 'is_active' => true],
            ['name' => 'Parasitology', 'description' => 'Animal parasite specialist', 'is_active' => true],
            ['name' => 'Nutrition & Feed', 'description' => 'Animal nutrition expert', 'is_active' => true],
            ['name' => 'Vaccination Programs', 'description' => 'Vaccination specialist', 'is_active' => true],
            ['name' => 'General Practice', 'description' => 'General veterinary practice', 'is_active' => true],
        ];

        foreach ($specializations as $spec) {
            Specialization::create($spec);
        }

        // Service Areas
        $serviceAreas = [
            ['name' => 'Urban Areas', 'description' => 'Serves urban and suburban areas', 'is_active' => true],
            ['name' => 'Rural Communities', 'description' => 'Serves rural farming communities', 'is_active' => true],
            ['name' => 'Commercial Farms', 'description' => 'Large commercial operations', 'is_active' => true],
            ['name' => 'Smallholder Farms', 'description' => 'Small-scale farmers', 'is_active' => true],
            ['name' => 'Pastoral Communities', 'description' => 'Nomadic and pastoral herders', 'is_active' => true],
            ['name' => 'Emergency Response', 'description' => 'Emergency and outbreak response', 'is_active' => true],
            ['name' => 'Mobile Clinic', 'description' => 'Mobile veterinary services', 'is_active' => true],
            ['name' => 'Clinic-Based', 'description' => 'Fixed clinic location', 'is_active' => true],
        ];

        foreach ($serviceAreas as $area) {
            ServiceArea::create($area);
        }
    }
}
