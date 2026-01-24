<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Check if columns don't already exist before adding
            if (!Schema::hasColumn('users', 'country_id')) {
                $table->foreignId('country_id')->nullable()->after('address')->constrained('countries')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('users', 'state_id')) {
                $table->foreignId('state_id')->nullable()->after('country_id')->constrained('states')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('users', 'lga_id')) {
                $table->foreignId('lga_id')->nullable()->after('state_id')->constrained('lgas')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('users', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('lga_id')->comment('GPS Latitude');
            }
            
            if (!Schema::hasColumn('users', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude')->comment('GPS Longitude');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'country_id')) {
                $table->dropForeign(['country_id']);
                $table->dropColumn('country_id');
            }
            
            if (Schema::hasColumn('users', 'state_id')) {
                $table->dropForeign(['state_id']);
                $table->dropColumn('state_id');
            }
            
            if (Schema::hasColumn('users', 'lga_id')) {
                $table->dropForeign(['lga_id']);
                $table->dropColumn('lga_id');
            }
            
            if (Schema::hasColumn('users', 'latitude')) {
                $table->dropColumn('latitude');
            }
            
            if (Schema::hasColumn('users', 'longitude')) {
                $table->dropColumn('longitude');
            }
        });
    }
};