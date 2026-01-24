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
        // Add account_status column if it doesn't exist
        if (!Schema::hasColumn('users', 'account_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('account_status', ['active', 'inactive', 'suspended', 'banned'])
                    ->default('active')
                    ->after('status');
            });

            // Copy existing status values to account_status for all users
            DB::table('users')->update([
                'account_status' => DB::raw('CASE 
                    WHEN status = "active" THEN "active"
                    WHEN status = "suspended" THEN "suspended"
                    WHEN status = "pending" THEN "active"
                    ELSE "active"
                END')
            ]);
        }

        // Add latitude and longitude if they don't exist
        if (!Schema::hasColumn('users', 'latitude')) {
            Schema::table('users', function (Blueprint $table) {
                $table->decimal('latitude', 10, 7)->nullable()->after('lga_id');
            });
        }

        if (!Schema::hasColumn('users', 'longitude')) {
            Schema::table('users', function (Blueprint $table) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            });
        }

        // Update role enum to include new roles
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'farmer', 'animal_health_professional', 'volunteer', 'data_collector', 'individual') DEFAULT 'farmer'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'account_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('account_status');
            });
        }

        if (Schema::hasColumn('users', 'latitude')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('latitude');
            });
        }

        if (Schema::hasColumn('users', 'longitude')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('longitude');
            });
        }
    }
};