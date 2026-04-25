<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('livestock')) {
            return;
        }

        if (Schema::hasColumn('livestock', 'farm_record_id')) {
            Schema::table('livestock', function (Blueprint $table) {
                $table->unsignedBigInteger('farm_record_id')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('livestock')) {
            return;
        }

        if (Schema::hasColumn('livestock', 'farm_record_id')) {
            Schema::table('livestock', function (Blueprint $table) {
                $table->unsignedBigInteger('farm_record_id')->nullable(false)->change();
            });
        }
    }
};
