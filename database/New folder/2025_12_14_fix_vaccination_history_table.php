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
        Schema::table('vaccination_history', function (Blueprint $table) {
            // Make farm_record_id nullable if it exists
            if (Schema::hasColumn('vaccination_history', 'farm_record_id')) {
                $table->unsignedBigInteger('farm_record_id')->nullable()->change();
            }
            
            // Make user_id nullable if it exists
            if (Schema::hasColumn('vaccination_history', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->change();
            }
            
            // Make disease_target nullable if it exists
            if (Schema::hasColumn('vaccination_history', 'disease_target')) {
                $table->string('disease_target')->nullable()->change();
            }
            
            // Make livestock_type nullable if it exists
            if (Schema::hasColumn('vaccination_history', 'livestock_type')) {
                $table->string('livestock_type')->nullable()->change();
            }
            
            // Add next_vaccination_date if it doesn't exist
            if (!Schema::hasColumn('vaccination_history', 'next_vaccination_date')) {
                $table->date('next_vaccination_date')->nullable()->after('vaccination_date');
            }
            
            // Add administered_by if it doesn't exist
            if (!Schema::hasColumn('vaccination_history', 'administered_by')) {
                $table->string('administered_by')->nullable()->after('vaccination_date');
            }
            
            // Add batch_number if it doesn't exist
            if (!Schema::hasColumn('vaccination_history', 'batch_number')) {
                $table->string('batch_number')->nullable()->after('administered_by');
            }
            
            // Add notes if it doesn't exist
            if (!Schema::hasColumn('vaccination_history', 'notes')) {
                $table->text('notes')->nullable()->after('batch_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vaccination_history', function (Blueprint $table) {
            // Revert nullable changes (if needed)
            if (Schema::hasColumn('vaccination_history', 'next_vaccination_date')) {
                $table->dropColumn('next_vaccination_date');
            }
        });
    }
};