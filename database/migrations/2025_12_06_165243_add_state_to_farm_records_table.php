<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('farm_records', function (Blueprint $table) {
            if (!Schema::hasColumn('farm_records', 'state')) {
                $table->string('state')->nullable()->after('id'); 
            }
        });
    }

    public function down(): void
    {
        Schema::table('farm_records', function (Blueprint $table) {
            if (Schema::hasColumn('farm_records', 'state')) {
                $table->dropColumn('state');
            }
        });
    }
};
