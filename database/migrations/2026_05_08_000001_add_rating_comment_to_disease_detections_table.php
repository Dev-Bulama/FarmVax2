<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disease_detections', function (Blueprint $table) {
            $table->tinyInteger('user_rating')->unsigned()->nullable()->after('ai_model');
            $table->text('user_comment')->nullable()->after('user_rating');
        });
    }

    public function down(): void
    {
        Schema::table('disease_detections', function (Blueprint $table) {
            $table->dropColumn(['user_rating', 'user_comment']);
        });
    }
};
