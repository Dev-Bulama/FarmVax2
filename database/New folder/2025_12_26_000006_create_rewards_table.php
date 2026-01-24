<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->integer('points')->default(0);
            $table->enum('type', ['enrollment', 'activity', 'milestone', 'bonus'])->default('activity');
            $table->string('badge')->nullable(); // bronze, silver, gold, platinum
            $table->timestamps();
        });

        Schema::create('volunteer_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('volunteer_id')->constrained('volunteers')->onDelete('cascade');
            $table->integer('total_enrollments')->default(0);
            $table->integer('active_farmers')->default(0);
            $table->integer('total_points')->default(0);
            $table->string('current_badge')->default('bronze');
            $table->integer('rank')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_stats');
        Schema::dropIfExists('rewards');
    }
};
