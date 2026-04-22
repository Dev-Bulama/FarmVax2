<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Safely create chatbot_conversations if it doesn't exist at all
        if (!Schema::hasTable('chatbot_conversations')) {
            Schema::create('chatbot_conversations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('session_id')->nullable();
                $table->enum('status', ['active', 'closed'])->default('active');
                $table->boolean('is_active')->default(true);
                $table->boolean('human_requested')->default(false);
                $table->timestamp('human_requested_at')->nullable();
                $table->boolean('human_takeover')->default(false);
                $table->timestamp('human_takeover_at')->nullable();
                $table->unsignedBigInteger('handled_by_admin_id')->nullable();
                $table->boolean('notification_sent')->default(false);
                $table->timestamps();
            });
        } else {
            Schema::table('chatbot_conversations', function (Blueprint $table) {
                if (!Schema::hasColumn('chatbot_conversations', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('status');
                }
                if (!Schema::hasColumn('chatbot_conversations', 'human_requested')) {
                    $table->boolean('human_requested')->default(false);
                }
                if (!Schema::hasColumn('chatbot_conversations', 'human_requested_at')) {
                    $table->timestamp('human_requested_at')->nullable();
                }
                if (!Schema::hasColumn('chatbot_conversations', 'human_takeover')) {
                    $table->boolean('human_takeover')->default(false);
                }
                if (!Schema::hasColumn('chatbot_conversations', 'human_takeover_at')) {
                    $table->timestamp('human_takeover_at')->nullable();
                }
                if (!Schema::hasColumn('chatbot_conversations', 'handled_by_admin_id')) {
                    $table->unsignedBigInteger('handled_by_admin_id')->nullable();
                }
                if (!Schema::hasColumn('chatbot_conversations', 'notification_sent')) {
                    $table->boolean('notification_sent')->default(false);
                }
            });
        }

        // Safely create chatbot_messages if it doesn't exist
        if (!Schema::hasTable('chatbot_messages')) {
            Schema::create('chatbot_messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('conversation_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->text('message');
                $table->enum('sender_type', ['user', 'bot', 'admin'])->default('user');
                $table->timestamps();

                $table->foreign('conversation_id')
                    ->references('id')->on('chatbot_conversations')
                    ->onDelete('cascade');
            });
        }

        // Ensure chatbot_training_data exists
        if (!Schema::hasTable('chatbot_training_data')) {
            Schema::create('chatbot_training_data', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->enum('type', ['text', 'url', 'document'])->default('text');
                $table->text('content');
                $table->string('source_url')->nullable();
                $table->string('category')->default('general');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Non-destructive rollback – only remove columns we may have added
        if (Schema::hasTable('chatbot_conversations') && Schema::hasColumn('chatbot_conversations', 'is_active')) {
            Schema::table('chatbot_conversations', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }
};
