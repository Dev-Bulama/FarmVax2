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
            // Make all potentially missing fields nullable
            
            if (Schema::hasColumn('service_requests', 'service_title')) {
                $table->string('service_title')->nullable()->default('Service Request')->change();
            }
            
            if (Schema::hasColumn('service_requests', 'service_description')) {
                $table->text('service_description')->nullable()->change();
            }
            
            if (Schema::hasColumn('service_requests', 'description')) {
                $table->text('description')->nullable()->change();
            }
            
            if (Schema::hasColumn('service_requests', 'location')) {
                $table->text('location')->nullable()->change();
            }
            
            if (Schema::hasColumn('service_requests', 'urgency_level')) {
                $table->string('urgency_level')->nullable()->default('medium')->change();
            }
            
            if (Schema::hasColumn('service_requests', 'currency')) {
                $table->string('currency')->nullable()->default('NGN')->change();
            }
            
            if (Schema::hasColumn('service_requests', 'payment_status')) {
                $table->string('payment_status')->nullable()->default('unpaid')->change();
            }
            
            if (Schema::hasColumn('service_requests', 'preferred_contact_method')) {
                $table->string('preferred_contact_method')->nullable()->default('phone')->change();
            }
            
            if (Schema::hasColumn('service_requests', 'reference_number')) {
                $table->string('reference_number')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't reverse - we want to keep nullable fields
    }
};