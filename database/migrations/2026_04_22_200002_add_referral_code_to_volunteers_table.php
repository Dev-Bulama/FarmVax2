<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('volunteers', function (Blueprint $table) {
            if (!Schema::hasColumn('volunteers', 'referral_code')) {
                $table->string('referral_code', 20)->nullable()->unique()->after('notes');
            }
            if (!Schema::hasColumn('volunteers', 'status')) {
                $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('motivation');
            }
        });
    }

    public function down(): void
    {
        Schema::table('volunteers', function (Blueprint $table) {
            if (Schema::hasColumn('volunteers', 'referral_code')) {
                $table->dropColumn('referral_code');
            }
        });
    }
};
