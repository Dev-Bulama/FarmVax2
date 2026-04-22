<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telemedicine_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requester_id');  // farmer or any user
            $table->unsignedBigInteger('professional_id')->nullable(); // vet assigned
            $table->unsignedBigInteger('livestock_id')->nullable();    // animal being discussed
            $table->string('room_code', 64)->unique();                 // Jitsi room identifier
            $table->enum('status', ['pending', 'assigned', 'in_progress', 'completed', 'cancelled'])
                  ->default('pending');
            $table->text('reason');                                    // reason for request
            $table->text('notes')->nullable();                         // user notes
            $table->text('professional_notes')->nullable();            // vet notes after call
            $table->text('admin_notes')->nullable();
            $table->string('priority', 20)->default('normal');         // normal | urgent
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->unsignedSmallInteger('duration_minutes')->nullable();
            $table->boolean('is_follow_up')->default(false);
            $table->unsignedBigInteger('parent_request_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('requester_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('professional_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('livestock_id')->references('id')->on('livestock')->onDelete('set null');
            $table->index('status');
            $table->index('requester_id');
            $table->index('professional_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telemedicine_requests');
    }
};
