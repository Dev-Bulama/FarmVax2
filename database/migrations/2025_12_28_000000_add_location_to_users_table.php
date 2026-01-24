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
            // Add columns ONLY if they don't exist
            // All are nullable so existing users are not affected
            if (!Schema::hasColumn('users', 'country_id')) {
                $table->unsignedBigInteger('country_id')->nullable()->after('address');
            }
            if (!Schema::hasColumn('users', 'state_id')) {
                $table->unsignedBigInteger('state_id')->nullable()->after('country_id');
            }
            if (!Schema::hasColumn('users', 'lga_id')) {
                $table->unsignedBigInteger('lga_id')->nullable()->after('state_id');
            }
        });

        // Add indexes for better performance
        // Wrapped in try-catch to avoid errors if indexes already exist
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->index('country_id');
                $table->index('state_id');
                $table->index('lga_id');
            });
        } catch (\Exception $e) {
            // Indexes might already exist, continue
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop indexes
            try {
                $table->dropIndex(['country_id']);
                $table->dropIndex(['state_id']);
                $table->dropIndex(['lga_id']);
            } catch (\Exception $e) {
                // Indexes might not exist, continue
            }
            
            // Drop columns
            if (Schema::hasColumn('users', 'country_id')) {
                $table->dropColumn('country_id');
            }
            if (Schema::hasColumn('users', 'state_id')) {
                $table->dropColumn('state_id');
            }
            if (Schema::hasColumn('users', 'lga_id')) {
                $table->dropColumn('lga_id');
            }
        });
    }
};