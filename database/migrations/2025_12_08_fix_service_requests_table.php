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
        Schema::table('service_requests', function (Blueprint $table) {
            // Make service_title nullable and add default value
            if (Schema::hasColumn('service_requests', 'service_title')) {
                $table->string('service_title')->nullable()->default('Service Request')->change();
            }
            
            // Add description column if it doesn't exist
            if (!Schema::hasColumn('service_requests', 'description')) {
                $table->text('description')->nullable()->after('service_type');
            }
            
            // Add location column if it doesn't exist
            if (!Schema::hasColumn('service_requests', 'location')) {
                $table->text('location')->nullable()->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            // Don't reverse the nullable change to avoid breaking existing records
        });
    }
};