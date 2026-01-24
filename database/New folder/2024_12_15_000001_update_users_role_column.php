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
            // Update role enum to include new roles
            // Drop and recreate the role column with new values
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            // Add updated role column with new values
            $table->enum('role', [
                'admin',
                'farmer',
                'animal_health_professional',
                'volunteer'
            ])->default('farmer')->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            // Restore old role column
            $table->enum('role', [
                'admin',
                'individual',
                'data_collector'
            ])->default('individual')->after('password');
        });
    }
};