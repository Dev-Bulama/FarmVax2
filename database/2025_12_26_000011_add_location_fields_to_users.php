<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('country_id')->nullable()->after('phone')->constrained('countries')->onDelete('set null');
            $table->foreignId('state_id')->nullable()->after('country_id')->constrained('states')->onDelete('set null');
            $table->foreignId('lga_id')->nullable()->after('state_id')->constrained('lgas')->onDelete('set null');
            $table->decimal('latitude', 10, 7)->nullable()->after('address');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->enum('account_status', ['active', 'suspended', 'deactivated', 'banned'])->default('active')->after('status');
        });

        Schema::table('animal_health_professionals', function (Blueprint $table) {
            $table->foreignId('professional_type_id')->nullable()->after('user_id')->constrained('professional_types')->onDelete('set null');
            $table->foreignId('specialization_id')->nullable()->after('professional_type_id')->constrained('specializations')->onDelete('set null');
            $table->foreignId('service_area_id')->nullable()->after('specialization_id')->constrained('service_areas')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropForeign(['state_id']);
            $table->dropForeign(['lga_id']);
            $table->dropColumn(['country_id', 'state_id', 'lga_id', 'latitude', 'longitude', 'account_status']);
        });

        Schema::table('animal_health_professionals', function (Blueprint $table) {
            $table->dropForeign(['professional_type_id']);
            $table->dropForeign(['specialization_id']);
            $table->dropForeign(['service_area_id']);
            $table->dropColumn(['professional_type_id', 'specialization_id', 'service_area_id']);
        });
    }
};
