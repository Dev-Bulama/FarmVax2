<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate existing data_collector users to animal_health_professional
        DB::table('users')
            ->where('role', 'data_collector')
            ->update(['role' => 'animal_health_professional']);

        // Migrate data from data_collectors table to animal_health_professionals table
        $dataCollectors = DB::table('data_collectors')->get();

        foreach ($dataCollectors as $collector) {
            DB::table('animal_health_professionals')->insert([
                'user_id' => $collector->user_id,
                'professional_type' => 'others', // Default to 'others' - can be updated later
                'license_number' => null,
                'organization' => $collector->organization ?? null,
                'experience_years' => $collector->experience_years ?? 0,
                'specialization' => null,
                'assigned_territory' => $collector->assigned_territory ?? null,
                'contact_phone' => $collector->contact_phone ?? null,
                'contact_email' => $collector->contact_email ?? null,
                'approval_status' => $collector->approval_status ?? 'pending',
                'approved_by' => $collector->approved_by ?? null,
                'approved_at' => $collector->approved_at ?? null,
                'rejection_reason' => $collector->rejection_reason ?? null,
                'submitted_at' => $collector->submitted_at ?? null,
                'application_notes' => $collector->application_notes ?? null,
                'verification_documents' => $collector->verification_documents ?? null,
                'created_at' => $collector->created_at ?? now(),
                'updated_at' => $collector->updated_at ?? now(),
            ]);
        }

        // Migrate existing individual users to farmer
        DB::table('users')
            ->where('role', 'individual')
            ->update(['role' => 'farmer']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert animal_health_professional users back to data_collector
        DB::table('users')
            ->where('role', 'animal_health_professional')
            ->update(['role' => 'data_collector']);

        // Revert farmer users back to individual
        DB::table('users')
            ->where('role', 'farmer')
            ->update(['role' => 'individual']);

        // Note: Data from animal_health_professionals table won't be migrated back
        // as it will be handled by dropping the table in the table migration
    }
};