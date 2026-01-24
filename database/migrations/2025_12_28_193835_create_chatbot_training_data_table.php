<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_training_data', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['text', 'url', 'document'])->default('text');
            $table->text('content');
            $table->string('source_url')->nullable();
            $table->string('category')->default('general');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_training_data');
    }
};