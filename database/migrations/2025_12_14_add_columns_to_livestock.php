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
        Schema::table('livestock', function (Blueprint $table) {
            // Add type column if it doesn't exist
            if (!Schema::hasColumn('livestock', 'type')) {
                $table->string('type')->after('owner_id');
            }
            
            // Add breed column if it doesn't exist
            if (!Schema::hasColumn('livestock', 'breed')) {
                $table->string('breed')->nullable()->after('type');
            }
            
            // Add tag_number column if it doesn't exist
            if (!Schema::hasColumn('livestock', 'tag_number')) {
                $table->string('tag_number')->nullable()->after('breed');
            }
            
            // Add date_of_birth column if it doesn't exist
            if (!Schema::hasColumn('livestock', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('tag_number');
            }
            
            // Add gender column if it doesn't exist
            if (!Schema::hasColumn('livestock', 'gender')) {
                $table->enum('gender', ['male', 'female'])->nullable()->after('date_of_birth');
            }
            
            // Add health_status column if it doesn't exist
            if (!Schema::hasColumn('livestock', 'health_status')) {
                $table->string('health_status')->default('healthy')->after('gender');
            }
            
            // Add notes column if it doesn't exist
            if (!Schema::hasColumn('livestock', 'notes')) {
                $table->text('notes')->nullable()->after('health_status');
            }
            
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('livestock', 'status')) {
                $table->enum('status', ['active', 'sold', 'deceased'])->default('active')->after('notes');
            }
            
            // Add weight_unit column if it doesn't exist
            if (!Schema::hasColumn('livestock', 'weight_unit')) {
                $table->string('weight_unit')->default('kg')->after('status');
            }
            
            // Add age_category column if it doesn't exist
            if (!Schema::hasColumn('livestock', 'age_category')) {
                $table->string('age_category')->default('unknown')->after('weight_unit');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livestock', function (Blueprint $table) {
            $table->dropColumn([
                'type',
                'breed',
                'tag_number',
                'date_of_birth',
                'gender',
                'health_status',
                'notes',
                'status',
                'weight_unit',
                'age_category'
            ]);
        });
    }
};