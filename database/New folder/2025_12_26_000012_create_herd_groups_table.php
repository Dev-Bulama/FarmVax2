<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('herd_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('animal_type'); // cattle, goats, sheep, etc.
            $table->integer('total_animals')->default(0);
            $table->decimal('average_health_score', 5, 2)->default(0);
            $table->timestamps();
        });

        Schema::table('livestock', function (Blueprint $table) {
            $table->foreignId('herd_group_id')->nullable()->after('user_id')->constrained('herd_groups')->onDelete('set null');
            $table->decimal('health_score', 5, 2)->nullable()->after('status');
            $table->date('last_checkup')->nullable()->after('health_score');
        });
    }

    public function down(): void
    {
        Schema::table('livestock', function (Blueprint $table) {
            $table->dropForeign(['herd_group_id']);
            $table->dropColumn(['herd_group_id', 'health_score', 'last_checkup']);
        });

        Schema::dropIfExists('herd_groups');
    }
};
