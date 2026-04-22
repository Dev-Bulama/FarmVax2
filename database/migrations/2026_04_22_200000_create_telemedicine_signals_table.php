<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('telemedicine_signals')) {
            return;
        }

        Schema::create('telemedicine_signals', function (Blueprint $table) {
            $table->id();
            $table->string('room_code', 191);
            $table->enum('from_role', ['caller', 'callee']);
            $table->enum('type', ['offer', 'answer', 'ice', 'hangup']);
            $table->longText('payload');
            $table->timestamps();

            $table->index(['room_code', 'from_role', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telemedicine_signals');
    }
};
