<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_versions', function (Blueprint $table) {
            $table->id();
            $table->string('version', 20); // e.g., "2.0.1"
            $table->string('release_name', 100)->nullable(); // e.g., "FarmVax Production Update"
            $table->text('description')->nullable();
            $table->text('changelog')->nullable(); // Detailed changes
            $table->string('update_file_path')->nullable(); // Path to ZIP file
            $table->string('update_file_size')->nullable();
            $table->enum('status', ['pending', 'applied', 'failed', 'rolled_back'])->default('pending');
            $table->timestamp('applied_at')->nullable();
            $table->foreignId('applied_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('error_log')->nullable();
            $table->boolean('requires_migration')->default(false);
            $table->boolean('requires_cache_clear')->default(true);
            $table->boolean('requires_restart')->default(false);
            $table->json('backup_info')->nullable(); // Info about backups created
            $table->boolean('is_current')->default(false);
            $table->timestamps();

            // Ensure only one version is marked as current
            $table->index('is_current');
            $table->index('status');
        });

        // Insert current version
        DB::table('system_versions')->insert([
            'version' => '1.0.0',
            'release_name' => 'FarmVax Initial Release',
            'description' => 'Initial production system',
            'status' => 'applied',
            'applied_at' => now(),
            'is_current' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('system_versions');
    }
};
