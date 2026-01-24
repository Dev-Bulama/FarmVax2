<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('herd_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., "Dairy Cows", "Breeding Goats"
            $table->string('type')->nullable(); // cattle, goats, sheep, etc.
            $table->text('description')->nullable();
            $table->string('purpose')->nullable(); // dairy, meat, breeding, mixed
            $table->integer('total_count')->default(0);
            $table->integer('healthy_count')->default(0);
            $table->integer('sick_count')->default(0);
            $table->string('location')->nullable();
            $table->string('color_code')->default('#2fcb6e'); // For visual identification
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Add herd_group_id to livestock table
        Schema::table('livestock', function (Blueprint $table) {
            $table->foreignId('herd_group_id')->nullable()->after('user_id')->constrained('herd_groups')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('livestock', function (Blueprint $table) {
            $table->dropForeign(['herd_group_id']);
            $table->dropColumn('herd_group_id');
        });
        
        Schema::dropIfExists('herd_groups');
    }
};