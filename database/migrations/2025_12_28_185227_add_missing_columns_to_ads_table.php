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
        Schema::table('ads', function (Blueprint $table) {
            // Check if columns exist before adding
            if (!Schema::hasColumn('ads', 'type')) {
                $table->string('type')->default('banner')->after('category');
            }
            if (!Schema::hasColumn('ads', 'link_url')) {
$table->string('link_url')->nullable();            }
            if (!Schema::hasColumn('ads', 'target_type')) {
                $table->string('target_type')->default('all')->after('link_url');
            }
            if (!Schema::hasColumn('ads', 'targeting_data')) {
                $table->text('targeting_data')->nullable()->after('target_type');
            }
            if (!Schema::hasColumn('ads', 'country_id')) {
                $table->unsignedBigInteger('country_id')->nullable()->after('targeting_data');
            }
            if (!Schema::hasColumn('ads', 'state_id')) {
                $table->unsignedBigInteger('state_id')->nullable()->after('country_id');
            }
            if (!Schema::hasColumn('ads', 'lga_id')) {
                $table->unsignedBigInteger('lga_id')->nullable()->after('state_id');
            }
            if (!Schema::hasColumn('ads', 'priority')) {
                $table->integer('priority')->default(50)->after('lga_id');
            }
            if (!Schema::hasColumn('ads', 'image_path')) {
                $table->string('image_path')->nullable()->after('content');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn([
                'type',
                'link_url',
                'target_type',
                'targeting_data',
                'country_id',
                'state_id',
                'lga_id',
                'priority',
                'image_path'
            ]);
        });
    }
};