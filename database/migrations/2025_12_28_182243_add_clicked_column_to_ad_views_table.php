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
        Schema::table('ad_views', function (Blueprint $table) {
            $table->boolean('clicked')->default(false)->after('user_id');
            $table->timestamp('clicked_at')->nullable()->after('clicked');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_views', function (Blueprint $table) {
            $table->dropColumn(['clicked', 'clicked_at']);
        });
    }
};