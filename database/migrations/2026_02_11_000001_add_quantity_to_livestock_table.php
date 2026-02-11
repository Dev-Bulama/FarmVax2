<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('livestock', function (Blueprint $table) {
            // quantity: number of animals this record represents (e.g. flock of 20,000 chickens)
            $table->unsignedInteger('quantity')->default(1)->after('type')
                  ->comment('Number of animals this record represents (for batch/flock entries)');
        });
    }

    public function down(): void
    {
        Schema::table('livestock', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
};
