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
        Schema::table('volunteers', function (Blueprint $table) {
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('volunteers', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active')->after('motivation');
            }
            
            // Add other potentially missing columns
            if (!Schema::hasColumn('volunteers', 'availability')) {
                $table->string('availability')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('volunteers', 'farmers_enrolled')) {
                $table->integer('farmers_enrolled')->default(0)->after('availability');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('volunteers', function (Blueprint $table) {
            $table->dropColumn(['status', 'availability', 'farmers_enrolled']);
        });
    }
};