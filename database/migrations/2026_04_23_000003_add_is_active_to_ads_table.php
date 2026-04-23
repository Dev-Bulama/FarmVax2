<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('ads', 'is_active')) {
            Schema::table('ads', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('status');
            });

            // Sync from existing status column
            DB::table('ads')->where('status', 'active')->update(['is_active' => 1]);
            DB::table('ads')->where('status', '!=', 'active')->update(['is_active' => 0]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('ads', 'is_active')) {
            Schema::table('ads', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }
};
