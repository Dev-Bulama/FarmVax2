<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('outbreak_alerts', 'is_active')) {
            Schema::table('outbreak_alerts', function (Blueprint $table) {
                $table->boolean('is_active')->default(1)->after('status');
            });

            // Sync with existing status data
            DB::table('outbreak_alerts')->where('status', 'active')->update(['is_active' => 1]);
            DB::table('outbreak_alerts')->where('status', '!=', 'active')->update(['is_active' => 0]);
        }
    }

    public function down(): void
    {
        Schema::table('outbreak_alerts', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
